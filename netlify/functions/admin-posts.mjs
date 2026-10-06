// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-posts
//  CRUD écritures sur public.posts, réservé aux sessions admin
//  valides (vérifiées via GoTrue). Lecture publique : passe par
//  Supabase REST directement (RLS "select public"), pas ici.
// ══════════════════════════════════════════════════════════════
import { requireAdmin, supabaseAdminHeaders, supabaseUrl } from "./lib/adminAuth.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), { status, headers: { "Content-Type": "application/json" } });

// Le site est pré-rendu statiquement (scripts/build-static.sh) : une écriture
// Supabase seule ne suffit pas à mettre à jour sparklin.io. On déclenche donc
// un rebuild Netlify à chaque création/modification/suppression d'article.
async function triggerRebuild() {
  const hook = process.env.NETLIFY_BUILD_HOOK_URL;
  if (!hook) return;
  try {
    await fetch(hook, { method: "POST" });
  } catch {
    // Un échec de déclenchement ne doit pas faire échouer l'enregistrement du post.
  }
}

// IndexNow : notifie Bing/Yandex/Seznam/Naver qu'une URL est nouvelle ou mise
// à jour, sans attendre leur prochain crawl du sitemap. Le fichier de
// vérification (<key>.txt) est copié à la racine par build-static.sh.
async function triggerIndexNow(url) {
  const key = process.env.INDEXNOW_KEY;
  if (!key || !url) return;
  try {
    const keyLocation = `https://sparklin.io/${key}.txt`;
    const endpoint = `https://api.indexnow.org/indexnow?url=${encodeURIComponent(url)}&key=${key}&keyLocation=${encodeURIComponent(keyLocation)}`;
    await fetch(endpoint);
  } catch {
    // Best-effort, ne doit pas faire échouer l'enregistrement du post.
  }
}

function slugify(str) {
  return String(str || "")
    .toLowerCase()
    .normalize("NFD").replace(/[̀-ͯ]/g, "")
    .replace(/[^a-z0-9]+/g, "-")
    .replace(/^-+|-+$/g, "");
}

export default async (req) => {
  if (req.method === "OPTIONS") return new Response(null, { status: 204 });

  const user = await requireAdmin(req);
  if (!user) return json(401, { error: "Non autorisé" });

  try {
    if (req.method === "POST") {
      const body = await req.json();
      const title = String(body.title || "").trim();
      if (!title) return json(400, { error: "Titre requis" });

      const status = ["draft", "published", "scheduled"].includes(body.status) ? body.status : "draft";
      const row = {
        slug: slugify(body.slug || title),
        title,
        excerpt: body.excerpt ? String(body.excerpt) : null,
        category: body.category ? String(body.category) : "Actualité",
        status,
        author: body.author ? String(body.author) : "Mhaia",
        published_at: body.published_at ? new Date(body.published_at).toISOString() : (status === "published" ? new Date().toISOString() : null),
        meta_title: body.meta_title ? String(body.meta_title) : null,
        meta_desc: body.meta_desc ? String(body.meta_desc) : null,
        keywords: body.keywords ? String(body.keywords) : null,
        hero_image: body.hero_image ? String(body.hero_image) : null,
        hero_image_alt: body.hero_image_alt ? String(body.hero_image_alt) : null,
        body_html: body.body_html ? String(body.body_html) : null,
        read_time: body.read_time ? String(body.read_time) : null,
        sort_order: Number.isFinite(body.sort_order) ? body.sort_order : 0,
      };
      if (!row.slug) return json(400, { error: "Slug invalide" });

      const resp = await fetch(supabaseUrl("posts"), {
        method: "POST",
        headers: { ...supabaseAdminHeaders(), Prefer: "return=representation" },
        body: JSON.stringify(row),
      });
      if (!resp.ok) {
        const detail = await resp.text();
        if (resp.status === 409 || detail.includes("duplicate")) return json(409, { error: "Ce slug existe déjà" });
        return json(502, { error: "Échec de création", detail });
      }
      const created = (await resp.json())[0];
      if (row.status === "published") {
        await triggerRebuild();
        await triggerIndexNow(`https://sparklin.io/blog/${row.slug}/`);
      }
      return json(201, { ok: true, post: created });
    }

    if (req.method === "PATCH") {
      const body = await req.json();
      const id = parseInt(body.id, 10);
      if (!id) return json(400, { error: "id requis" });

      const patch = { updated_at: new Date().toISOString() };
      for (const k of ["title", "excerpt", "category", "status", "author", "meta_title", "meta_desc", "keywords", "hero_image", "hero_image_alt", "body_html", "read_time", "sort_order"]) {
        if (body[k] !== undefined) patch[k] = body[k];
      }
      if (body.slug !== undefined) {
        const s = slugify(body.slug);
        if (!s) return json(400, { error: "Slug invalide" });
        patch.slug = s;
      }
      if (body.published_at !== undefined) {
        patch.published_at = body.published_at ? new Date(body.published_at).toISOString() : null;
      }
      if (patch.status && !["draft", "published", "scheduled"].includes(patch.status)) return json(400, { error: "status invalide" });

      const resp = await fetch(supabaseUrl("posts", `id=eq.${id}`), {
        method: "PATCH",
        headers: { ...supabaseAdminHeaders(), Prefer: "return=representation" },
        body: JSON.stringify(patch),
      });
      if (!resp.ok) {
        const detail = await resp.text();
        if (resp.status === 409 || detail.includes("duplicate")) return json(409, { error: "Ce slug existe déjà" });
        return json(502, { error: "Échec de mise à jour", detail });
      }
      const updated = (await resp.json())[0];
      // Rebuild si le statut a été changé explicitement (publication/dépublication/
      // planification) ou si un post déjà publié vient d'être modifié.
      if (patch.status !== undefined || updated.status === "published") {
        await triggerRebuild();
        if (updated.status === "published") await triggerIndexNow(`https://sparklin.io/blog/${updated.slug}/`);
      }
      return json(200, { ok: true, post: updated });
    }

    if (req.method === "DELETE") {
      const { searchParams } = new URL(req.url);
      const id = parseInt(searchParams.get("id") || "", 10);
      if (!id) return json(400, { error: "id requis" });

      const resp = await fetch(supabaseUrl("posts", `id=eq.${id}`), {
        method: "DELETE",
        headers: supabaseAdminHeaders(),
      });
      if (!resp.ok) return json(502, { error: "Échec de suppression" });
      // On ne sait pas ici si le post supprimé était publié (donc déjà présent
      // dans dist/) sans lecture préalable — on rebuild systématiquement par
      // sécurité, le coût d'un build superflu est négligeable face au risque
      // de laisser une page supprimée visible en production.
      await triggerRebuild();
      return json(200, { ok: true });
    }

    return json(405, { error: "Method not allowed" });
  } catch (err) {
    return json(500, { error: "Erreur serveur", detail: String(err) });
  }
};
