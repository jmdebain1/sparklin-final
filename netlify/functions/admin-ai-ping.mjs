// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/admin-ai-ping
//  Sonde synchrone et rapide : la clé Anthropic est-elle configurée
//  côté serveur ? N'appelle jamais l'API Anthropic, ne consomme pas
//  de tokens. Utilisée par l'onglet Paramètres de l'admin-blog.
// ══════════════════════════════════════════════════════════════
import { requireAdmin } from "./lib/adminAuth.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), { status, headers: { "Content-Type": "application/json" } });

export default async (req) => {
  if (req.method === "OPTIONS") return new Response(null, { status: 204 });

  const user = await requireAdmin(req);
  if (!user) return json(401, { error: "Non autorisé" });

  return json(200, { configured: !!process.env.ANTHROPIC_API_KEY });
};
