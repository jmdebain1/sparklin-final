/**
 * Vérifie qu'une requête admin porte un token de session Supabase valide.
 * Le front (admin-blog) envoie le access_token stocké après connexion par
 * lien magique — cette fonction le valide auprès de GoTrue (Supabase Auth)
 * avant d'autoriser toute écriture avec la clé service_role.
 */
export async function requireAdmin(req) {
  const auth = req.headers.get("authorization") || "";
  const token = auth.startsWith("Bearer ") ? auth.slice(7) : "";
  if (!token) return null;

  const url = (process.env.SUPABASE_URL || "").replace(/\/$/, "");
  const anonKey = process.env.SUPABASE_ANON_KEY || "";
  if (!url || !anonKey) return null;

  try {
    const resp = await fetch(`${url}/auth/v1/user`, {
      headers: { apikey: anonKey, Authorization: `Bearer ${token}` },
    });
    if (!resp.ok) return null;
    const user = await resp.json();
    return user && user.id ? user : null;
  } catch {
    return null;
  }
}

export function supabaseAdminHeaders() {
  const key = process.env.SUPABASE_SECRET || process.env.SUPABASE_SERVICE_ROLE_KEY || "";
  return {
    apikey: key,
    Authorization: `Bearer ${key}`,
    "Content-Type": "application/json",
  };
}

export function supabaseUrl(table, query = "") {
  const base = (process.env.SUPABASE_URL || "").replace(/\/$/, "");
  return `${base}/rest/v1/${table}${query ? `?${query}` : ""}`;
}
