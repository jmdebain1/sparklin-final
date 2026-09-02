// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/review-campaign-send  (scheduled — vendredi)
//  Envoie le rappel "avis Google" à chaque employé encore en
//  attente (status=pending) et déjà inscrit (enroll_date <= aujourd'hui).
//  Répété chaque semaine tant que l'employé n'a pas cliqué
//  "J'ai déjà laissé mon avis" (→ review-campaign-done).
// ══════════════════════════════════════════════════════════════
import { supabaseAdminHeaders, supabaseUrl } from "./lib/adminAuth.mjs";

const GOOGLE_REVIEW_LINK = "https://www.google.com/search?q=Sparklin+Avis";
const SITE_URL = "https://sparklin.io";

async function brevoSendEmail(payload) {
  const key = process.env.BREVO_API_KEY || "";
  const resp = await fetch("https://api.brevo.com/v3/smtp/email", {
    method: "POST",
    headers: { "api-key": key, "Content-Type": "application/json", accept: "application/json" },
    body: JSON.stringify(payload),
  });
  return resp.status;
}

export default async () => {
  const today = new Date().toISOString().slice(0, 10);
  const templateId = parseInt(process.env.BREVO_TEMPLATE_REVIEW_CAMPAIGN_ID || "4", 10);

  const resp = await fetch(
    supabaseUrl("review_campaign", `select=*&status=eq.pending&enroll_date=lte.${today}`),
    { headers: supabaseAdminHeaders() }
  );
  if (!resp.ok) {
    return new Response(JSON.stringify({ error: "Échec de lecture review_campaign" }), { status: 502 });
  }
  const rows = await resp.json();

  let sent = 0;
  for (const row of rows) {
    const firstName = (row.employee_name || "").split(" ")[0] || row.employee_name;
    const doneUrl = `${SITE_URL}/.netlify/functions/review-campaign-done?token=${encodeURIComponent(row.token)}`;

    const code = await brevoSendEmail({
      templateId,
      to: [{ email: row.employee_email, name: row.employee_name }],
      params: {
        NAME: firstName,
        REVIEW: row.review_text,
        GOOGLE_LINK: GOOGLE_REVIEW_LINK,
        DONE_URL: doneUrl,
      },
    });

    if (code >= 200 && code < 300) {
      sent++;
      await fetch(supabaseUrl("review_campaign", `id=eq.${row.id}`), {
        method: "PATCH",
        headers: supabaseAdminHeaders(),
        body: JSON.stringify({
          reminders_sent: (row.reminders_sent || 0) + 1,
          last_sent_at: new Date().toISOString(),
        }),
      });
    }
  }

  return new Response(JSON.stringify({ ok: true, candidates: rows.length, sent }), {
    status: 200,
    headers: { "Content-Type": "application/json" },
  });
};

export const config = { schedule: "0 7 * * 5" };
