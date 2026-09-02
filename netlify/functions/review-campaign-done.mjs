// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/review-campaign-done?token=…
//  Lien "J'ai déjà laissé mon avis" cliqué depuis l'email —
//  marque l'employé comme fait pour qu'il n'y ait plus de rappel.
// ══════════════════════════════════════════════════════════════
import { supabaseAdminHeaders, supabaseUrl } from "./lib/adminAuth.mjs";

function page(title, message, ok) {
  return `<!DOCTYPE html>
<html lang="fr"><head><meta charset="utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>${title}</title></head>
<body style="margin:0;padding:0;background:#f4f4f7;font-family:'Helvetica Neue',Arial,sans-serif;color:#1a1a2e;display:flex;min-height:100vh;align-items:center;justify-content:center;">
  <div style="max-width:440px;width:100%;background:#fff;border-radius:16px;box-shadow:0 6px 24px rgba(26,26,46,.08);padding:40px 36px;text-align:center;margin:20px;">
    <div style="font-size:40px;margin-bottom:12px;">${ok ? "✅" : "⚠️"}</div>
    <h1 style="font-size:20px;font-weight:800;margin:0 0 10px;">${title}</h1>
    <p style="font-size:14.5px;line-height:1.7;color:#555;margin:0;">${message}</p>
    <a href="https://sparklin.io" style="display:inline-block;margin-top:24px;font-size:13px;color:#E8563A;text-decoration:none;font-weight:600;">← Retour à sparklin.io</a>
  </div>
</body></html>`;
}

const html = (body, status = 200) =>
  new Response(body, { status, headers: { "Content-Type": "text/html; charset=utf-8" } });

export default async (req) => {
  const { searchParams } = new URL(req.url);
  const token = (searchParams.get("token") || "").trim();

  if (!token) {
    return html(page("Lien invalide", "Ce lien de confirmation est incomplet.", false), 400);
  }

  const resp = await fetch(
    supabaseUrl("review_campaign", `select=*&token=eq.${encodeURIComponent(token)}&limit=1`),
    { headers: supabaseAdminHeaders() }
  );
  const rows = resp.ok ? await resp.json() : [];
  const row = rows[0];

  if (!row) {
    return html(page("Lien invalide", "Ce lien de confirmation n'existe pas ou a expiré.", false), 404);
  }

  if (row.status !== "done") {
    await fetch(supabaseUrl("review_campaign", `id=eq.${row.id}`), {
      method: "PATCH",
      headers: supabaseAdminHeaders(),
      body: JSON.stringify({ status: "done", done_at: new Date().toISOString() }),
    });
  }

  return html(
    page(
      "Merci beaucoup ! 🙏",
      `C'est noté${row.employee_name ? `, ${row.employee_name.split(" ")[0]}` : ""} — vous ne recevrez plus de rappel pour cet avis Google. Merci pour votre soutien !`,
      true
    )
  );
};
