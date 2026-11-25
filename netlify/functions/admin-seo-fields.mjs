// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-seo-fields
//  À partir du titre + du texte d'un article, génère l'extrait (résumé public),
//  la méta description et le mot-clé principal. Appelée par l'éditeur de
//  l'admin blog quand on colle un article entier.
//  POST { title?, text } → { excerpt, metaDescription, focusKeyword, title? }
// ══════════════════════════════════════════════════════════════
import { requireAdmin } from "./lib/adminAuth.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), { status, headers: { "Content-Type": "application/json" } });

export default async (req) => {
  if (req.method !== "POST") return json(405, { error: "Method not allowed" });
  if (!(await requireAdmin(req))) return json(401, { error: "Non autorisé" });

  let body;
  try {
    body = await req.json();
  } catch {
    return json(400, { error: "Corps de requête invalide" });
  }
  const title = String(body.title || "").slice(0, 300);
  const text = String(body.text || "").trim().slice(0, 24000);
  if (text.length < 80) return json(400, { error: "Texte trop court" });

  const apiKey = process.env.ANTHROPIC_API_KEY;
  if (!apiKey) return json(500, { error: "ANTHROPIC_API_KEY manquante" });

  const prompt = `Tu es rédacteur SEO pour le blog de Sparklin (bornes de recharge intelligentes pour véhicules électriques, IRVE). Voici un article, dans sa langue d'origine (généralement le français).

Titre actuel : ${title || "(aucun)"}

Texte de l'article :
"""
${text}
"""

Produis, DANS LA MÊME LANGUE que l'article :
- "excerpt" : résumé public de 1 à 2 phrases, 140 à 220 caractères, accrocheur, sans guillemets ni markdown.
- "metaDescription" : méta description SEO de 120 à 158 caractères MAXIMUM, avec le mot-clé principal, qui donne envie de cliquer.
- "focusKeyword" : le mot-clé principal (2 à 4 mots, en minuscules) que cet article devrait cibler, présent dans le texte.
- "title" : uniquement si le titre actuel est vide, un titre de 50 à 65 caractères ; sinon renvoie la chaîne vide.

Réponds UNIQUEMENT avec un objet JSON avec exactement ces 4 clés.`;

  let resp;
  try {
    resp = await fetch("https://api.anthropic.com/v1/messages", {
      method: "POST",
      headers: { "Content-Type": "application/json", "x-api-key": apiKey, "anthropic-version": "2023-06-01" },
      body: JSON.stringify({ model: "claude-sonnet-5", max_tokens: 1000, messages: [{ role: "user", content: prompt }] }),
    });
  } catch {
    return json(502, { error: "Impossible de contacter l'API Anthropic" });
  }
  if (!resp.ok) return json(502, { error: `Erreur API Anthropic (HTTP ${resp.status})` });

  try {
    const data = await resp.json();
    const raw = (data.content || []).map((b) => b.text || "").join("").trim();
    const out = JSON.parse(raw.slice(raw.indexOf("{"), raw.lastIndexOf("}") + 1));
    const clip = (s, n) => {
      s = String(s || "").replace(/\s+/g, " ").trim();
      if (s.length <= n) return s;
      const cut = s.slice(0, n - 1);
      return cut.slice(0, cut.lastIndexOf(" ") > n * 0.6 ? cut.lastIndexOf(" ") : cut.length).replace(/[\s,;:.-]+$/, "") + "…";
    };
    return json(200, {
      excerpt: clip(out.excerpt, 240),
      metaDescription: clip(out.metaDescription, 160),
      focusKeyword: String(out.focusKeyword || "").toLowerCase().trim().slice(0, 80),
      title: title ? "" : clip(out.title, 90),
    });
  } catch {
    return json(502, { error: "Réponse illisible" });
  }
};
