// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-events
//  CRUD écritures sur public.events, réservé aux sessions admin
//  valides (vérifiées via GoTrue). Lecture publique : passe par
//  Supabase REST directement (RLS "select public"), pas ici.
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
      const row = {
        status: body.status === "past" ? "past" : "upcoming",
        icon: String(body.icon || "⚡"),
        icon_gradient: String(body.icon_gradient || "linear-gradient(135deg,#B23A1E,#E8563A)"),
        badge: body.badge ? String(body.badge) : null,
        title: String(body.title || "").trim(),
        description: body.description ? String(body.description) : null,
        highlights: Array.isArray(body.highlights) ? body.highlights.filter(Boolean).map(String) : [],
        link_url: body.link_url ? String(body.link_url) : null,
        link_label: body.link_label ? String(body.link_label) : null,
        sort_order: Number.isFinite(body.sort_order) ? body.sort_order : 0,
      };
      if (!row.title) return json(400, { error: "Titre requis" });

      const resp = await fetch(supabaseUrl("events"), {
        method: "POST",
        headers: { ...supabaseAdminHeaders(), Prefer: "return=representation" },
        body: JSON.stringify(row),
      });
      if (!resp.ok) return json(502, { error: "Échec de création", detail: await resp.text() });
      return json(201, { ok: true, event: (await resp.json())[0] });
    }

    if (req.method === "PATCH") {
      const body = await req.json();
      const id = parseInt(body.id, 10);
      if (!id) return json(400, { error: "id requis" });

      const patch = { updated_at: new Date().toISOString() };
      for (const k of ["status", "icon", "icon_gradient", "badge", "title", "description", "link_url", "link_label", "sort_order"]) {
        if (body[k] !== undefined) patch[k] = body[k];
      }
      if (body.highlights !== undefined) {
        patch.highlights = Array.isArray(body.highlights) ? body.highlights.filter(Boolean).map(String) : [];
      }
      if (patch.status && !["upcoming", "past"].includes(patch.status)) return json(400, { error: "status invalide" });

      const resp = await fetch(supabaseUrl("events", `id=eq.${id}`), {
        method: "PATCH",
        headers: { ...supabaseAdminHeaders(), Prefer: "return=representation" },
        body: JSON.stringify(patch),
      });
      if (!resp.ok) return json(502, { error: "Échec de mise à jour", detail: await resp.text() });
      return json(200, { ok: true, event: (await resp.json())[0] });
    }

    if (req.method === "DELETE") {
      const { searchParams } = new URL(req.url);
      const id = parseInt(searchParams.get("id") || "", 10);
      if (!id) return json(400, { error: "id requis" });

      const resp = await fetch(supabaseUrl("events", `id=eq.${id}`), {
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
