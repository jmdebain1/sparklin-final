// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-ai-generate-status?jobId=...
//  Sondage (polling) léger de l'état d'un job démarré par
//  admin-ai-generate (fonction en arrière-plan). Lit la table
//  Supabase ai_generation_jobs. Réservé aux sessions admin valides.
// ══════════════════════════════════════════════════════════════
import { requireAdmin, supabaseAdminHeaders, supabaseUrl } from "./lib/adminAuth.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), { status, headers: { "Content-Type": "application/json" } });

export default async (req) => {
  if (req.method === "OPTIONS") return new Response(null, { status: 204 });

  const user = await requireAdmin(req);
  if (!user) return json(401, { error: "Non autorisé" });

  const jobId = new URL(req.url).searchParams.get("jobId");
  if (!jobId) return json(400, { error: "jobId manquant" });

  try {
    const resp = await fetch(supabaseUrl("ai_generation_jobs", `id=eq.${jobId}&select=status,result,error`), {
      headers: supabaseAdminHeaders(),
    });
    if (!resp.ok) return json(502, { error: "Lecture du job impossible" });
    const rows = await resp.json();
    if (!rows || !rows.length) return json(200, { status: "pending" }); // job pas encore écrit (course possible au tout début)
    return json(200, rows[0]);
  } catch (e) {
    console.error("admin-ai-generate-status: erreur", e);
    return json(500, { error: "Erreur interne" });
  }
};
