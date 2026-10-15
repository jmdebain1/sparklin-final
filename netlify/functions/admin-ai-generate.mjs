// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-ai-generate  (fonction EN ARRIÈRE-PLAN)
//  Démarre une génération d'article via l'API Anthropic. Le client
//  reçoit un 202 immédiat (la génération peut prendre 30s-2min pour
//  un article complet, largement au-delà des 60s d'une fonction
//  synchrone) ; le résultat est écrit dans la table Supabase
//  ai_generation_jobs, à lire via admin-ai-generate-status.
//  Réservé aux sessions admin valides (vérifiées via GoTrue).
// ══════════════════════════════════════════════════════════════
import { requireAdmin, supabaseAdminHeaders, supabaseUrl } from "./lib/adminAuth.mjs";
import { runGeneration } from "./lib/aiGenerate.mjs";

async function writeJob(id, fields) {
  try {
    await fetch(supabaseUrl("ai_generation_jobs"), {
      method: "POST",
      headers: { ...supabaseAdminHeaders(), Prefer: "resolution=merge-duplicates" },
      body: JSON.stringify({ id, updated_at: new Date().toISOString(), ...fields }),
    });
  } catch (e) {
    console.error("admin-ai-generate: écriture du job impossible", e);
  }
}

async function cleanupOldJobs() {
  try {
    const cutoff = new Date(Date.now() - 24 * 60 * 60 * 1000).toISOString();
    await fetch(supabaseUrl("ai_generation_jobs", `created_at=lt.${cutoff}`), {
      method: "DELETE",
      headers: supabaseAdminHeaders(),
    });
  } catch {
    // best-effort
  }
}

export default async (req) => {
  let body;
  try {
    body = await req.json();
  } catch {
    return; // réponse déjà discardée (background), rien à communiquer
  }

  const jobId = body.jobId;
  if (!jobId) return;

  const user = await requireAdmin(req);
  if (!user) {
    await writeJob(jobId, { status: "error", error: "Non autorisé" });
    return;
  }

  cleanupOldJobs();
  await writeJob(jobId, { status: "pending" });

  const { result, error, detail } = await runGeneration({
    mode: body.mode,
    tone: body.tone,
    title: body.title,
    keywords: body.keywords,
    wordGoal: body.wordGoal,
    lang: body.lang,
  });

  if (error) {
    await writeJob(jobId, { status: "error", error: detail ? `${error} — ${detail}` : error });
  } else {
    await writeJob(jobId, { status: "done", result });
  }
};

export const config = { background: true };
