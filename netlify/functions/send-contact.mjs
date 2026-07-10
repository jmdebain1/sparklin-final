// ══════════════════════════════════════════════════════════════
//  /.netlify/functions/send-contact
//  Formulaire de contact → notification email via Brevo (+ lead Supabase)
//  Portage de api/send-contact.php — même logique, même comportement.
// ══════════════════════════════════════════════════════════════
import { recaptchaVerify } from "./lib/recaptcha.mjs";
import { brevoAddContact, brevoSendEmail, supabaseInsertLead } from "./lib/brevo.mjs";

const json = (status, obj) =>
  new Response(JSON.stringify(obj), { status, headers: { "Content-Type": "application/json" } });

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
  const nom = clean(body.nom);
  const entreprise = clean(body.entreprise);
  const type = clean(body.type_besoin);
  const bornes = clean(body.nb_bornes);
  const message = clean(body.message);
  const source = ["contact", "support"].includes(body.source) ? body.source : "contact";

  if (!email) return json(400, { error: "Email invalide" });

  // Anti-abus reCAPTCHA v3
  const captchaOk = await recaptchaVerify(body.recaptcha_token, "contact");
  if (!captchaOk) return json(429, { error: "Vérification anti-robot échouée" });

  // 1. Lead Supabase (best-effort)
  await supabaseInsertLead({
    email, name: nom, company: entreprise, source, message,
    meta: { type_besoin: type, nb_bornes: bornes },
    created_at: new Date().toISOString(),
  });

  // 2. Notification email à l'équipe
  const brevoKey = process.env.BREVO_API_KEY || "";
  const fromEmail = process.env.BREVO_FROM_EMAIL || "jean-mael.debain@sparklin.io";
  const fromName = process.env.BREVO_FROM_NAME || "Sparklin";
  const toEmail = process.env.CONTACT_TO_EMAIL || "contact@sparklin.io";

  if (!brevoKey) return json(500, { error: "Brevo non configuré" });

  const html =
    `<h2>Nouvelle demande de contact</h2>` +
    `<p><strong>Nom :</strong> ${nom || "—"}</p>` +
    `<p><strong>Email :</strong> ${email}</p>` +
    `<p><strong>Entreprise :</strong> ${entreprise || "—"}</p>` +
    `<p><strong>Type de besoin :</strong> ${type || "—"}</p>` +
    `<p><strong>Nombre de bornes :</strong> ${bornes || "—"}</p>` +
    `<p><strong>Message :</strong><br>${(message || "—").replace(/\n/g, "<br>")}</p>`;

  const code = await brevoSendEmail({
    sender: { name: fromName, email: fromEmail },
    to: [{ email: toEmail }],
    replyTo: { email, name: nom || email },
    subject: `Contact site${entreprise ? ` — ${entreprise}` : ""}${nom ? ` (${nom})` : ""}`,
    htmlContent: html,
  });

  if (code >= 200 && code < 300) {
    // Inscription liste "Contact & Support" (best-effort)
    await brevoAddContact(email, process.env.BREVO_LIST_CONTACT_ID || 0, { NOM: nom });

    // Accusé de réception visiteur (best-effort, via template Brevo)
    const ackTpl = parseInt(process.env.BREVO_TEMPLATE_CONTACT_ACK_ID || "0", 10);
    if (ackTpl) {
      await brevoSendEmail({
        templateId: ackTpl,
        to: [{ email, name: nom || email }],
        params: { NAME: nom },
      });
    }

    return json(200, { ok: true });
  }

  return json(502, { error: "Envoi échoué", brevo_code: code });
};
