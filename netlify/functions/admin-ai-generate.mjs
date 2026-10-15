// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-ai-generate
//  Génère un article de blog (ou une partie) via l'API Anthropic,
//  en injectant la base de connaissance Sparklin + les candidats
//  de maillage interne/image, et en forçant une sortie JSON
//  structurée (titre, corps, meta, image, liens...).
//  Réservé aux sessions admin valides (vérifiées via GoTrue).
// ══════════════════════════════════════════════════════════════
import { requireAdmin, supabaseAdminHeaders, supabaseUrl } from "./lib/adminAuth.mjs";
import { SPARKLIN_KNOWLEDGE } from "./lib/sparklinKnowledge.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), { status, headers: { "Content-Type": "application/json" } });

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

export default async (req) => {
  if (req.method === "OPTIONS") return new Response(null, { status: 204 });
  if (req.method !== "POST") return json(405, { error: "Méthode non autorisée" });

  const user = await requireAdmin(req);
  if (!user) return json(401, { error: "Non autorisé" });

  const apiKey = process.env.ANTHROPIC_API_KEY;

  let body;
  try {
    body = await req.json();
  } catch {
    return json(400, { error: "Corps de requête invalide" });
  }

  // Simple sonde de configuration pour l'onglet Paramètres de l'admin (ne
  // consomme pas de tokens, n'appelle pas l'API Anthropic).
  if (body.ping) return json(200, { configured: !!apiKey });

  if (!apiKey) return json(400, { error: "Clé API Anthropic non configurée côté serveur (variable ANTHROPIC_API_KEY manquante)." });

  const mode = Object.keys(MODES).includes(body.mode) ? body.mode : "full";
  const tone = Object.keys(TONES).includes(body.tone) ? body.tone : "expert";

  const [internalLinks, images] = await Promise.all([fetchInternalLinkCandidates(), fetchImageCandidates()]);

  const prompt = buildPrompt({
    mode,
    tone,
    title: body.title,
    keywords: Array.isArray(body.keywords) ? body.keywords : [],
    wordGoal: body.wordGoal,
    lang: body.lang,
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
        max_tokens: 4096,
        system: SPARKLIN_KNOWLEDGE,
        messages: [{ role: "user", content: prompt }],
      }),
    });
  } catch {
    return json(502, { error: "Impossible de contacter l'API Anthropic" });
  }

  if (!resp.ok) {
    const errText = await resp.text().catch(() => "");
    return json(resp.status, { error: "Erreur API Anthropic", detail: errText.slice(0, 500) });
  }

  const data = await resp.json();
  const text = (data.content || []).map((b) => b.text || "").join("").trim();

  let parsed;
  try {
    const jsonStart = text.indexOf("{");
    const jsonEnd = text.lastIndexOf("}");
    parsed = JSON.parse(text.slice(jsonStart, jsonEnd + 1));
  } catch {
    return json(502, { error: "Réponse IA non interprétable", raw: text.slice(0, 1000) });
  }

  return json(200, parsed);
};
