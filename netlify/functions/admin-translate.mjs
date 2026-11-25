// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-translate
//  Traduit en anglais des textes de l'admin (titres d'articles, événements,
//  presse, aperçu d'article) quand l'interface admin est en anglais.
//  POST { texts: string[], html?: boolean } → { translations: string[] }
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
  const texts = Array.isArray(body.texts) ? body.texts.map((t) => String(t ?? "")) : null;
  if (!texts || !texts.length || texts.length > 40) return json(400, { error: "texts invalide (1 à 40 éléments)" });
  if (texts.some((t) => t.length > 8000) || texts.join("").length > 30000) return json(400, { error: "Texte trop long" });

  const apiKey = process.env.ANTHROPIC_API_KEY;
  if (!apiKey) return json(500, { error: "ANTHROPIC_API_KEY manquante" });

  const prompt = `Translate each item of this JSON array into English (the source is usually French, from the website of Sparklin, a French maker of EV-charging stations; items may be short titles or sentences). Keep brand and product names (Sparklin, Spark Pilot, Spark 1, Spark Plus, Spark x go-e, GIREVE, OCPP, MID, LOM, URSSAF), numbers, units and proper nouns unchanged. If an item is already English, return it unchanged. ${
    body.html ? "Items contain HTML: keep every tag, attribute and URL exactly as is and translate only the text." : "Items are plain text: do not add markup."
  }
Reply with ONLY a JSON array of ${texts.length} strings, in the same order.

${JSON.stringify(texts)}`;

  let resp;
  try {
    resp = await fetch("https://api.anthropic.com/v1/messages", {
      method: "POST",
      headers: { "Content-Type": "application/json", "x-api-key": apiKey, "anthropic-version": "2023-06-01" },
      body: JSON.stringify({ model: "claude-sonnet-5", max_tokens: 8000, messages: [{ role: "user", content: prompt }] }),
    });
  } catch {
    return json(502, { error: "Impossible de contacter l'API Anthropic" });
  }
  if (!resp.ok) return json(502, { error: `Erreur API Anthropic (HTTP ${resp.status})` });

  try {
    const data = await resp.json();
    const text = (data.content || []).map((b) => b.text || "").join("").trim();
    const out = JSON.parse(text.slice(text.indexOf("["), text.lastIndexOf("]") + 1));
    if (!Array.isArray(out) || out.length !== texts.length) throw new Error("longueur");
    return json(200, { translations: out.map((t) => String(t)) });
  } catch {
    return json(502, { error: "Réponse de traduction illisible" });
  }
};
