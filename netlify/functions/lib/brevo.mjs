/**
 * Helper Brevo — ajoute/maj un contact dans une liste (best-effort).
 * Si des attributs personnalisés n'existent pas (400), on réessaie sans attributs
 * pour garantir l'inscription à la liste.
 */
export async function brevoAddContact(email, listId, attributes = {}) {
  const key = process.env.BREVO_API_KEY || "";
  listId = parseInt(listId, 10);
  if (!key || !listId || !email) return false;

  const post = async (payload) => {
    const resp = await fetch("https://api.brevo.com/v3/contacts", {
      method: "POST",
      headers: { "api-key": key, "Content-Type": "application/json", accept: "application/json" },
      body: JSON.stringify(payload),
    });
    return resp.status;
  };

  const base = { email, listIds: [listId], updateEnabled: true };
  const cleanAttrs = Object.fromEntries(
    Object.entries(attributes).filter(([, v]) => v !== "" && v != null)
  );
  const hasAttrs = Object.keys(cleanAttrs).length > 0;

  let code = await post(hasAttrs ? { ...base, attributes: cleanAttrs } : base);
  if (code >= 200 && code < 300) return true;
  // attribut inconnu, etc. → on retente sans attributs (l'inscription à la liste prime)
  if (hasAttrs) {
    code = await post(base);
    return code >= 200 && code < 300;
  }
  return false;
}

export async function brevoSendEmail(payload) {
  const key = process.env.BREVO_API_KEY || "";
  const resp = await fetch("https://api.brevo.com/v3/smtp/email", {
    method: "POST",
    headers: { "api-key": key, "Content-Type": "application/json", accept: "application/json" },
    body: JSON.stringify(payload),
  });
  return resp.status;
}

export async function supabaseInsertLead(row) {
  const url = (process.env.SUPABASE_URL || "").replace(/\/$/, "");
  const key = process.env.SUPABASE_SECRET || process.env.SUPABASE_SERVICE_ROLE_KEY || "";
  if (!url || !key) return;
  try {
    await fetch(`${url}/rest/v1/leads`, {
      method: "POST",
      headers: {
        apikey: key,
        Authorization: `Bearer ${key}`,
        "Content-Type": "application/json",
        Prefer: "return=minimal",
      },
      body: JSON.stringify(row),
    });
  } catch {
    // best-effort — ne bloque jamais l'envoi d'email
  }
}
