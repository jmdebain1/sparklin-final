/**
 * Traduit un article (table posts, rédigé en français) dans toutes les
 * langues du site et écrit le résultat dans post_translations.
 * Appelé en arrière-plan par admin-posts-translate.mjs.
 */
import { createHash } from "node:crypto";
import { supabaseAdminHeaders, supabaseUrl } from "./adminAuth.mjs";

export const TARGET_LANGS = {
  en: "English (international, clear)",
  de: 'German (formal "Sie")',
  es: "Spanish (neutral Spain/LatAm)",
  th: "Thai (polite, professional)",
  ms: "Malay (Malaysia)",
  id: "Indonesian (formal)",
  tr: 'Turkish (formal "siz")',
  vi: "Vietnamese (professional)",
  fil: 'Filipino (standard Filipino; keep common English tech terms such as "charging station", "load balancing", "app")',
};

const FIELDS = ["title", "excerpt", "category", "meta_title", "meta_desc", "hero_image_alt", "read_time", "body_html"];

// Empreinte du contenu source : une langue n'est retraduite que si elle manque
// ou si le texte français a changé depuis la dernière traduction.
export function sourceHash(post) {
  const source = {};
  for (const f of FIELDS) if (post[f]) source[f] = post[f];
  return createHash("sha1").update(JSON.stringify(source)).digest("hex");
}

const tagsOf = (html) =>
  (String(html || "").replace(/\s(alt|title|aria-label|placeholder)="[^"]*"/g, "").match(/<[^>]+>/g) || []).map((t) => t.replace(/\s+/g, " ").trim());
const hrefsOf = (html) => (String(html || "").match(/(?:href|src)="[^"]*"/g) || []).sort();

function sameStructure(src, out) {
  return JSON.stringify(tagsOf(src)) === JSON.stringify(tagsOf(out)) && JSON.stringify(hrefsOf(src)) === JSON.stringify(hrefsOf(out));
}

async function translateOne(post, lang, apiKey) {
  const source = {};
  for (const f of FIELDS) if (post[f]) source[f] = post[f];

  const prompt = `Translate this French blog article from Sparklin (French maker of smart EV-charging stations, "IRVE"; products Spark 1, Spark Plus, Spark x go-e, platform Spark Pilot) into ${TARGET_LANGS[lang]}.

RULES
- Translate faithfully and completely; do not summarize, add or drop content. SEO fields (title, meta_title, meta_desc) must read naturally for that market, meta_desc roughly <=160 characters.
- body_html is HTML: preserve every tag, attribute (class, style, id, href, src), entity and the structure exactly and in the same order. Translate only human-readable text (and alt/title attribute text). NEVER change URLs/hrefs (internal links such as /blog/... or /spark-1/ must stay identical), CSS values or ids.
- Keep brand/product names unchanged (Sparklin, Spark Pilot, Spark 1, Spark Plus, Spark x go-e, go-e, GIREVE, OCPP, MID, LOM, URSSAF, CEE, EDF). Keep numbers, units and € amounts. Translate unit words.
- Use the local natural term for EV charging infrastructure instead of the opaque French acronym "IRVE".
- Leave no French sentences behind.

Reply with ONLY one JSON object with exactly these keys (omit a key only if absent from the source): ${Object.keys(source).join(", ")}.

SOURCE JSON:
${JSON.stringify(source)}`;

  const resp = await fetch("https://api.anthropic.com/v1/messages", {
    method: "POST",
    headers: { "Content-Type": "application/json", "x-api-key": apiKey, "anthropic-version": "2023-06-01" },
    body: JSON.stringify({ model: "claude-sonnet-5", max_tokens: 16000, messages: [{ role: "user", content: prompt }] }),
  });
  if (!resp.ok) throw new Error(`Anthropic HTTP ${resp.status}`);
  const data = await resp.json();
  const text = (data.content || []).map((b) => b.text || "").join("").trim();
  const out = JSON.parse(text.slice(text.indexOf("{"), text.lastIndexOf("}") + 1));
  if (post.body_html && !sameStructure(post.body_html, out.body_html)) throw new Error("structure HTML différente");
  return out;
}

async function saveTranslation(postId, lang, out, hash) {
  const row = { post_id: postId, lang, source_hash: hash, updated_at: new Date().toISOString() };
  for (const f of FIELDS) if (out[f]) row[f] = String(out[f]);
  const resp = await fetch(supabaseUrl("post_translations", "on_conflict=post_id,lang"), {
    method: "POST",
    headers: { ...supabaseAdminHeaders(), Prefer: "resolution=merge-duplicates,return=minimal" },
    body: JSON.stringify(row),
  });
  if (!resp.ok) throw new Error(`Supabase HTTP ${resp.status}`);
}

/** Traduit le post `postId` dans toutes les langues ; renvoie { ok: [...], failed: [...] }. */
export async function translatePost(postId) {
  const apiKey = process.env.ANTHROPIC_API_KEY;
  if (!apiKey) throw new Error("ANTHROPIC_API_KEY manquante");

  const resp = await fetch(supabaseUrl("posts", `select=*&id=eq.${postId}&limit=1`), { headers: supabaseAdminHeaders() });
  const post = (await resp.json())[0];
  if (!post) throw new Error("post introuvable");

  const ok = [];
  const failed = [];
  const hash = sourceHash(post);
  const wanted = Array.isArray(post.languages) ? post.languages : null;
  const existing = await fetch(supabaseUrl("post_translations", `select=lang,source_hash&post_id=eq.${post.id}`), { headers: supabaseAdminHeaders() })
    .then((r) => (r.ok ? r.json() : []))
    .catch(() => []);
  const upToDate = new Set(existing.filter((r) => r.source_hash === hash).map((r) => r.lang));
  const langs = Object.keys(TARGET_LANGS).filter((l) => (!wanted || wanted.includes(l)) && !upToDate.has(l));
  for (let i = 0; i < langs.length; i += 3) {
    await Promise.all(
      langs.slice(i, i + 3).map(async (lang) => {
        for (let attempt = 0; attempt < 2; attempt++) {
          try {
            await saveTranslation(post.id, lang, await translateOne(post, lang, apiKey), hash);
            ok.push(lang);
            return;
          } catch (e) {
            if (attempt === 1) failed.push(`${lang}: ${e.message}`);
          }
        }
      })
    );
  }
  return { ok, failed };
}
