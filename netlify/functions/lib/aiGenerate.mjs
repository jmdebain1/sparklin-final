/**
 * Logique de génération d'article partagée par le worker en arrière-plan
 * (admin-ai-generate.mjs) : construit le prompt (base de connaissance +
 * maillage interne + images disponibles) et appelle l'API Anthropic.
 * Séparée dans ce module pour rester testable/lisible indépendamment du
 * handler HTTP et de l'écriture en base.
 */
import { supabaseAdminHeaders, supabaseUrl } from "./adminAuth.mjs";
import { SPARKLIN_KNOWLEDGE } from "./sparklinKnowledge.mjs";

const MODES = {
  full: "un article complet (introduction + corps structuré en H2/H3 + conclusion)",
  intro: "uniquement une introduction/accroche (2-3 paragraphes, pas de corps ni de conclusion)",
  outline: "un plan structuré (liste de titres H2 et sous-titres H3, sans rédiger le contenu de chaque section)",
  meta: "uniquement un titre SEO et une méta description, sans rédiger le corps de l'article",
};

const TONES = {
  expert: "expert et technique, assume que le lecteur connaît déjà les bases IRVE",
  simple: "accessible, pédagogique, évite le jargon ou l'explique immédiatement",
  commercial: "orienté conversion, met en avant les bénéfices et incite à demander un devis",
  edu: "pédagogique, structure façon guide pas-à-pas",
};

// Tourne désormais dans une fonction en arrière-plan (jusqu'à 15 minutes),
// plus dans une fonction synchrone à 60s — on peut se permettre un budget de
// tokens large pour un article complet de 2000 mots sans risque de
// troncature en plein JSON.
const MAX_TOKENS = {
  full: 8192,
  intro: 1024,
  outline: 2048,
  meta: 512,
};

async function fetchInternalLinkCandidates() {
  try {
    const url = supabaseUrl("posts", "select=title,slug,category&status=eq.published&limit=60");
    const resp = await fetch(url, { headers: supabaseAdminHeaders() });
    if (!resp.ok) return [];
    const rows = await resp.json();
    return (rows || []).map((r) => ({ title: r.title, url: `https://sparklin.io/blog/${r.slug}/`, category: r.category }));
  } catch {
    return [];
  }
}

async function fetchImageCandidates() {
  try {
    const resp = await fetch("https://sparklin.io/assets/images/manifest.json");
    if (!resp.ok) return [];
    return await resp.json();
  } catch {
    return [];
  }
}

function buildPrompt({ mode, tone, title, keywords, wordGoal, lang, internalLinks, images }) {
  const modeDesc = MODES[mode] || MODES.full;
  const toneDesc = TONES[tone] || TONES.expert;
  const wordTarget = Number(wordGoal) > 0 ? `Vise environ ${wordGoal} mots pour le corps de l'article.` : "Pas de contrainte stricte de longueur.";

  return `Rédige ${modeDesc} pour le blog IRVE de Sparklin, en langue "${lang || "fr"}".

Sujet / titre de travail : "${title || "(à déterminer à partir des mots-clés)"}"
Mots-clés ciblés : ${(keywords || []).join(", ") || "(aucun fourni)"}
Ton : ${toneDesc}
${wordTarget}

Liens internes disponibles (choisis 2 à 4 pertinents, n'en invente aucun autre, n'en force pas s'il n'y en a pas de pertinent) :
${internalLinks.length ? internalLinks.map((l) => `- ${l.title} → ${l.url} (catégorie: ${l.category || "n/a"})`).join("\n") : "(aucun article publié à lier pour le moment)"}

Images disponibles dans la bibliothèque (choisis la plus pertinente comme image de couverture ; si aucune ne convient, renvoie heroImage à null) :
${images.length ? images.map((i) => `- ${i.name} → ${i.path}`).join("\n") : "(bibliothèque vide)"}

Réponds UNIQUEMENT avec un objet JSON valide (pas de texte autour, pas de markdown code fence), avec exactement ces clés :
{
  "title": "titre de l'article, percutant, 50-60 caractères idéalement",
  "excerpt": "résumé public de 1-2 phrases",
  "metaDescription": "120 à 160 caractères, incite au clic",
  "focusKeyword": "le mot-clé principal choisi",
  "bodyHtml": "le corps de l'article en HTML simple (p, h2, h3, ul, li, strong, a href=...) — vide si mode=meta",
  "heroImage": "chemin de l'image choisie dans la liste ci-dessus, ou null",
  "heroAlt": "texte alternatif descriptif de l'image choisie, ou null",
  "tags": ["3 à 6 tags pertinents"],
  "internalLinksUsed": ["URLs effectivement insérées dans bodyHtml, parmi celles listées ci-dessus"],
  "externalLinksUsed": ["0 à 2 URLs externes autoritaires (ademe.fr, legifrance.gouv.fr, service-public.fr...) effectivement insérées dans bodyHtml, seulement si pertinent"]
}`;
}

/**
 * Lance la génération et renvoie soit { result }, soit { error, detail? }.
 * Ne lève jamais : tout échec (réseau, Anthropic non-ok, JSON tronqué) est
 * renvoyé comme { error } pour que l'appelant puisse l'écrire en base.
 */
export async function runGeneration({ mode: rawMode, tone: rawTone, title, keywords, wordGoal, lang }) {
  const apiKey = process.env.ANTHROPIC_API_KEY;
  if (!apiKey) return { error: "Clé API Anthropic non configurée côté serveur (variable ANTHROPIC_API_KEY manquante)." };

  const mode = Object.keys(MODES).includes(rawMode) ? rawMode : "full";
  const tone = Object.keys(TONES).includes(rawTone) ? rawTone : "expert";

  try {
    const [internalLinks, images] = await Promise.all([fetchInternalLinkCandidates(), fetchImageCandidates()]);

    const prompt = buildPrompt({
      mode,
      tone,
      title,
      keywords: Array.isArray(keywords) ? keywords : [],
      wordGoal,
      lang,
      internalLinks,
      images,
    });

    let resp;
    try {
      resp = await fetch("https://api.anthropic.com/v1/messages", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "x-api-key": apiKey,
          "anthropic-version": "2023-06-01",
        },
        body: JSON.stringify({
          model: "claude-sonnet-5",
          max_tokens: MAX_TOKENS[mode] || MAX_TOKENS.full,
          system: SPARKLIN_KNOWLEDGE,
          messages: [{ role: "user", content: prompt }],
        }),
      });
    } catch (e) {
      console.error("aiGenerate: fetch Anthropic a échoué", e);
      return { error: "Impossible de contacter l'API Anthropic" };
    }

    if (!resp.ok) {
      const errText = await resp.text().catch(() => "");
      console.error("aiGenerate: réponse Anthropic non-ok", resp.status, errText.slice(0, 1000));
      return { error: `Erreur API Anthropic (HTTP ${resp.status})`, detail: errText.slice(0, 500) };
    }

    let data;
    try {
      data = await resp.json();
    } catch (e) {
      console.error("aiGenerate: réponse Anthropic non-JSON", e);
      return { error: "Réponse Anthropic illisible" };
    }

    const text = (data.content || []).map((b) => b.text || "").join("").trim();

    try {
      const jsonStart = text.indexOf("{");
      const jsonEnd = text.lastIndexOf("}");
      const parsed = JSON.parse(text.slice(jsonStart, jsonEnd + 1));
      return { result: parsed };
    } catch (e) {
      console.error("aiGenerate: JSON du modèle non interprétable", e, text.slice(0, 500));
      return { error: "Réponse IA non interprétable", detail: text.slice(0, 1000) };
    }
  } catch (e) {
    console.error("aiGenerate: erreur inattendue", e);
    return { error: "Erreur interne inattendue", detail: String((e && e.message) || e).slice(0, 500) };
  }
}
