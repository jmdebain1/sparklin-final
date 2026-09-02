// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/send-livre-blanc
//  Reçoit le formulaire livre blanc, stocke le lead en Supabase,
//  envoie l'email via Brevo (template ou HTML par défaut).
//  Portage de api/send-livre-blanc.php — même logique, même comportement.
// ══════════════════════════════════════════════════════════════
import { recaptchaVerify } from "./lib/recaptcha.mjs";
import { brevoAddContact, brevoSendEmail, supabaseInsertLead } from "./lib/brevo.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), {
    status,
    headers: {
      "Content-Type": "application/json",
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "POST, OPTIONS",
      "Access-Control-Allow-Headers": "Content-Type",
    },
  });

const clean = (v) => String(v ?? "").trim();

export default async (req) => {
  if (req.method === "OPTIONS") return new Response(null, { status: 204 });
  if (req.method !== "POST") return json(405, { error: "Method not allowed" });

  let body = {};
  try {
    body = await req.json();
  } catch {
    return json(400, { error: "Corps de requête invalide" });
  }

  const emailRaw = clean(body.email);
  const email = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailRaw) ? emailRaw : null;
  // le formulaire envoie "prenom" ; on accepte aussi "name" par compatibilité
  const name = clean(body.prenom || body.name);
  const company = clean(body.company || body.entreprise);

  if (!email) return json(400, { error: "Email invalide" });

  // Anti-abus reCAPTCHA v3
  const captchaOk = await recaptchaVerify(body.recaptcha_token, "livre_blanc");
  if (!captchaOk) return json(429, { error: "Vérification anti-robot échouée" });

  // 1. Lead Supabase (best-effort)
  await supabaseInsertLead({
    email, name, company, source: "livre-blanc",
    created_at: new Date().toISOString(),
  });

  // 2. Email via Brevo (template si configuré, sinon HTML par défaut)
  const brevoKey = process.env.BREVO_API_KEY || "";
  const templateId = parseInt(process.env.BREVO_TEMPLATE_LIVREBLANC_ID || "0", 10);
  const pdfUrl = process.env.LIVREBLANC_PDF_URL || "https://sparklin.io/assets/livre-blanc-sparklin-2026.pdf";

  if (brevoKey) {
    const payload = templateId
      ? {
          templateId,
          to: [{ email, name }],
          params: { NAME: name, COMPANY: company, PDF_URL: pdfUrl },
        }
      : {
          sender: {
            name: process.env.BREVO_FROM_NAME || "Sparklin",
            email: process.env.BREVO_FROM_EMAIL || "jean-mael.debain@sparklin.io",
          },
          to: [{ email, name }],
          subject: "Votre guide Sparklin — Bornes de recharge en entreprise 2026",
          htmlContent:
            `<p>Bonjour ${name},</p>` +
            `<p>Voici votre exemplaire du guide complet de la recharge électrique en entreprise.</p>` +
            `<p><a href="${pdfUrl}">Télécharger le PDF</a></p>` +
            `<p>L'équipe Sparklin</p>`,
        };
    await brevoSendEmail(payload);
  }

  // 3. Inscription liste "Livre blanc" (best-effort)
  await brevoAddContact(email, process.env.BREVO_LIST_LIVREBLANC_ID || 0, { PRENOM: name });

  // 4. Notification à l'équipe (best-effort, ne bloque pas la réponse au visiteur)
  if (brevoKey) {
    const toEmail = process.env.CONTACT_TO_EMAIL || "contact@sparklin.io";
    const notifTpl = parseInt(process.env.BREVO_TEMPLATE_LIVREBLANC_NOTIF_ID || "3", 10);
    const notifPayload = notifTpl
      ? {
          templateId: notifTpl,
          to: [{ email: toEmail }],
          replyTo: { email, name: name || email },
          subject: `📥 Nouveau téléchargement du livre blanc${company ? ` — ${company}` : ""}${name ? ` (${name})` : ""}`,
          params: { NAME: name || "—", EMAIL: email, COMPANY: company || "—" },
        }
      : {
          sender: {
            name: process.env.BREVO_FROM_NAME || "Sparklin",
            email: process.env.BREVO_FROM_EMAIL || "jean-mael.debain@sparklin.io",
          },
          to: [{ email: toEmail }],
          replyTo: { email, name: name || email },
          subject: `Téléchargement livre blanc${company ? ` — ${company}` : ""}${name ? ` (${name})` : ""}`,
          htmlContent:
            `<h2>Nouveau téléchargement du livre blanc</h2>` +
            `<p><strong>Email :</strong> ${email}</p>` +
            `<p><strong>Prénom :</strong> ${name || "—"}</p>` +
            `<p><strong>Entreprise :</strong> ${company || "—"}</p>`,
        };
    await brevoSendEmail(notifPayload);
  }

  return json(200, { ok: true });
};
