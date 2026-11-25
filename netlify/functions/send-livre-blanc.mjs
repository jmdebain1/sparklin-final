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

// ── Livres blancs sectoriels ─────────────────────────────────────
// Clé = valeur du champ "guide" envoyé par le formulaire. Un guide inconnu ou
// absent = livre blanc « Entreprise » historique (comportement d'origine).
// Liste Brevo : surchargée par BREVO_LIST_LB_<CLE> si défini.
// null = pas encore de liste dédiée dans Brevo (le contact reçoit quand même
// le mail et le lead est enregistré dans Supabase).
const SITE = "https://sparklin.io";
const common = (a, b) => [
  `📍&nbsp; ${a}`,
  "⚖️&nbsp; Que dit la loi&nbsp;? Obligations LOM &amp; calendrier",
  "🔌&nbsp; Bien choisir sa solution Spark 1 / Spark Plus / go-e",
  "🛠️&nbsp; Installation, supervision (Spark Pilot) et maintenance",
  `💶&nbsp; ${b}`,
  "❓&nbsp; Conseils pratiques &amp; FAQ",
];
export const GUIDES = {
  entreprises: {
    label: "Entreprises", title: "Bornes de recharge en entreprise : le guide complet 2026",
    sector: "votre entreprise", list: 4,
    items: [
      "⚖️&nbsp; Réglementation 2024-2030&nbsp;: loi LOM, Décret Tertiaire, quotas de parking",
      "⚡&nbsp; Load balancing &amp; smart charging (Spark Plus, Spark Pilot)",
      "🔌&nbsp; Interopérabilité&nbsp;: OCPP 2.0 &amp; GIREVE",
      "👥&nbsp; RH &amp; fiscalité&nbsp;: remboursement URSSAF, avantage en nature",
      "💶&nbsp; Financement&nbsp;: Advenir 2026 et montage optimal",
      "🧩&nbsp; La gamme Spark 1 / Spark Plus / Spark Go-e / Spark Pilot",
    ],
  },
  collectivites: {
    label: "Collectivités", title: "La recharge électrique, enjeu stratégique pour les collectivités",
    sector: "votre collectivité", list: 10,
    items: [
      "📈&nbsp; Un contexte qui s'accélère&nbsp;: les chiffres qui comptent",
      "⚖️&nbsp; Le cadre réglementaire décrypté (LOM, DPEB)",
      "🔧&nbsp; Les enjeux techniques",
      "🏛️&nbsp; Quel modèle juridique pour votre territoire&nbsp;?",
      "💶&nbsp; Financer son projet IRVE",
      "🚀&nbsp; Déployer efficacement &amp; choisir ses partenaires",
    ],
  },
  gites: {
    label: "Gîtes & Airbnb", title: "Installer une borne de recharge dans votre gîte ou location saisonnière",
    sector: "votre gîte ou location saisonnière", list: 11,
    items: common("Pourquoi installer une borne chez vous&nbsp;? Airbnb, Booking, fidélisation", "Financer et rentabiliser son projet"),
  },
  golf: {
    label: "Golf", title: "Installer des bornes de recharge dans votre golf",
    sector: "votre golf", list: 9,
    items: common("Pourquoi installer des bornes dans votre golf&nbsp;? 4 à 5 h de parking plein", "Financer votre projet"),
  },
  hotels: {
    label: "Hôtels", title: "Installer des bornes de recharge dans votre hôtel",
    sector: "votre hôtel", list: null,
    items: common("Pourquoi équiper votre hôtel&nbsp;? Booking, clientèle électrique, nuitée", "Modèles économiques et financement"),
  },
  restaurants: {
    label: "Restaurants", title: "Installer des bornes de recharge dans votre restaurant",
    sector: "votre restaurant", list: null,
    items: common("Pourquoi installer des bornes dans votre restaurant&nbsp;? Le temps d'un repas = une recharge", "Modèles économiques et financement"),
  },
};
for (const [k, g] of Object.entries(GUIDES)) {
  g.pdf = `${SITE}/assets/livres-blancs/livre-blanc-sparklin-${k}-2026.pdf`;
  g.cover = `${SITE}/assets/images/livres-blancs/mockup-${k}.webp`;
}

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

  const guideKey = clean(body.guide).toLowerCase();
  const guide = GUIDES[guideKey] || null;

  // Anti-abus reCAPTCHA v3
  const captchaOk = await recaptchaVerify(body.recaptcha_token, "livre_blanc");
  if (!captchaOk) return json(429, { error: "Vérification anti-robot échouée" });

  // 1. Lead Supabase (best-effort)
  await supabaseInsertLead({
    email, name, company, source: guide ? `livre-blanc-${guideKey}` : "livre-blanc",
    created_at: new Date().toISOString(),
  });

  // 2. Email via Brevo (template si configuré, sinon HTML par défaut)
  const brevoKey = process.env.BREVO_API_KEY || "";
  const templateId = guide
    ? parseInt(process.env.BREVO_TEMPLATE_LB_SECTOR_ID || "30", 10)
    : parseInt(process.env.BREVO_TEMPLATE_LIVREBLANC_ID || "0", 10);
  const pdfUrl = guide ? guide.pdf : process.env.LIVREBLANC_PDF_URL || "https://sparklin.io/assets/livre-blanc-sparklin-2026.pdf";

  if (brevoKey) {
    const payload = templateId
      ? {
          templateId,
          to: [{ email, name }],
          params: guide
            ? { NAME: name, COMPANY: company, PDF_URL: pdfUrl, GUIDE_TITLE: guide.title, SECTOR: guide.sector, COVER_URL: guide.cover, ITEMS: guide.items }
            : { NAME: name, COMPANY: company, PDF_URL: pdfUrl },
        }
      : {
          sender: {
            name: process.env.BREVO_FROM_NAME || "Sparklin",
            email: process.env.BREVO_FROM_EMAIL || "jean-mael.debain@sparklin.io",
          },
          to: [{ email, name }],
          subject: guide ? `Votre guide Sparklin — ${guide.title}` : "Votre guide Sparklin — Bornes de recharge en entreprise 2026",
          htmlContent:
            `<p>Bonjour ${name},</p>` +
            `<p>Voici votre exemplaire du guide complet de la recharge électrique en entreprise.</p>` +
            `<p><a href="${pdfUrl}">Télécharger le PDF</a></p>` +
            `<p>L'équipe Sparklin</p>`,
        };
    await brevoSendEmail(payload);
  }

  // 3. Inscription liste "Livre blanc" (best-effort)
  const listId = guide
    ? process.env[`BREVO_LIST_LB_${guideKey.toUpperCase()}`] || guide.list || 0
    : process.env.BREVO_LIST_LIVREBLANC_ID || 0;
  await brevoAddContact(email, listId, { PRENOM: name });

  // 4. Notification à l'équipe (best-effort, ne bloque pas la réponse au visiteur)
  if (brevoKey) {
    const toEmail = process.env.CONTACT_TO_EMAIL || "contact@sparklin.io";
    const notifTpl = parseInt(process.env.BREVO_TEMPLATE_LIVREBLANC_NOTIF_ID || "3", 10);
    const notifPayload = notifTpl
      ? {
          templateId: notifTpl,
          to: [{ email: toEmail }],
          replyTo: { email, name: name || email },
          subject: `📥 Nouveau téléchargement du livre blanc${guide ? " " + guide.label : ""}${company ? ` — ${company}` : ""}${name ? ` (${name})` : ""}`,
          params: { NAME: name || "—", EMAIL: email, COMPANY: company || "—" },
        }
      : {
          sender: {
            name: process.env.BREVO_FROM_NAME || "Sparklin",
            email: process.env.BREVO_FROM_EMAIL || "jean-mael.debain@sparklin.io",
          },
          to: [{ email: toEmail }],
          replyTo: { email, name: name || email },
          subject: `Téléchargement livre blanc${guide ? " " + guide.label : ""}${company ? ` — ${company}` : ""}${name ? ` (${name})` : ""}`,
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
