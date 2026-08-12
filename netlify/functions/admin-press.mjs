// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-press
//  CRUD écritures sur public.press_mentions, réservé aux sessions
//  admin valides. Lecture publique : Supabase REST direct (RLS).
// ══════════════════════════════════════════════════════════════
import { requireAdmin, supabaseAdminHeaders, supabaseUrl } from "./lib/adminAuth.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), { status, headers: { "Content-Type": "application/json" } });

export default async (req) => {
  if (req.method === "OPTIONS") return new Response(null, { status: 204 });

  const user = await requireAdmin(req);
  if (!user) return json(401, { error: "Non autorisé" });

  try {
    if (req.method === "POST") {
      const body = await req.json();
      const url = String(body.url || "").trim();
      const title = String(body.title || "").trim();
      const source_name = String(body.source_name || "").trim();
      if (!url || !/^https?:\/\//i.test(url)) return json(400, { error: "URL invalide" });
      if (!title) return json(400, { error: "Titre requis" });
      if (!source_name) return json(400, { error: "Nom du média requis" });

      const row = {
        title,
        source_name,
        url,
        published_label: body.published_label ? String(body.published_label) : null,
        sort_order: Number.isFinite(body.sort_order) ? body.sort_order : 0,
      };

      const resp = await fetch(supabaseUrl("press_mentions"), {
        method: "POST",
        headers: { ...supabaseAdminHeaders(), Prefer: "return=representation" },
        body: JSON.stringify(row),
      });
      if (!resp.ok) return json(502, { error: "Échec de création", detail: await resp.text() });
      return json(201, { ok: true, press: (await resp.json())[0] });
    }

    if (req.method === "DELETE") {
      const { searchParams } = new URL(req.url);
      const id = parseInt(searchParams.get("id") || "", 10);
      if (!id) return json(400, { error: "id requis" });

      const resp = await fetch(supabaseUrl("press_mentions", `id=eq.${id}`), {
        method: "DELETE",
        headers: supabaseAdminHeaders(),
      });
      if (!resp.ok) return json(502, { error: "Échec de suppression" });
      return json(200, { ok: true });
    }

    return json(405, { error: "Method not allowed" });
  } catch (err) {
    return json(500, { error: "Erreur serveur", detail: String(err) });
  }
};
