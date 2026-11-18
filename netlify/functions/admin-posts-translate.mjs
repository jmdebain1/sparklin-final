// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-posts-translate  (fonction en arrière-plan)
//  Traduit un article publié dans toutes les langues du site, puis
//  relance un build Netlify pour que les pages traduites soient générées.
//  Appelée par admin-posts.mjs après publication/modification d'un post.
// ══════════════════════════════════════════════════════════════
import { requireAdmin } from "./lib/adminAuth.mjs";
import { translatePost } from "./lib/translatePost.mjs";

export default async (req) => {
  let body;
  try {
    body = await req.json();
  } catch {
    return;
  }
  const id = parseInt(body.id, 10);
  if (!id) return;
  if (!(await requireAdmin(req))) return;

  try {
    const { ok, failed } = await translatePost(id);
    console.log(`translate post ${id}: ok=[${ok}] failed=[${failed}]`);
    const hook = process.env.BUILD_HOOK_URL;
    if (hook && ok.length) await fetch(hook, { method: "POST" }).catch(() => {});
  } catch (e) {
    console.error("translate post échoué", id, e);
  }
};

export const config = { background: true };
