<?php
/* ══════════════════════════════════════════════════════════════
   /api/send-contact.php
   Formulaire de contact → notification email via Brevo (+ lead Supabase)
   ══════════════════════════════════════════════════════════════ */
require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/../includes/brevo.php';
require_once __DIR__ . '/../includes/recaptcha.php';
loadEnv(__DIR__ . '/../.env');

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error' => 'Method not allowed']); exit; }

// Accepte JSON ou form-urlencoded
$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);
if (!is_array($body)) $body = $_POST;

$clean = fn($v) => htmlspecialchars(trim((string)($v ?? '')), ENT_QUOTES, 'UTF-8');
$email      = filter_var(trim($body['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$nom        = $clean($body['nom'] ?? '');
$entreprise = $clean($body['entreprise'] ?? '');
$type       = $clean($body['type_besoin'] ?? '');
$bornes     = $clean($body['nb_bornes'] ?? '');
$message    = $clean($body['message'] ?? '');
// contact ou support → même liste Brevo
$source     = in_array(($body['source'] ?? ''), ['contact','support'], true) ? $body['source'] : 'contact';

if (!$email) { http_response_code(400); echo json_encode(['error' => 'Email invalide']); exit; }

// Anti-abus reCAPTCHA v3
if (!recaptcha_verify($body['recaptcha_token'] ?? null, 'contact')) {
    http_response_code(429); echo json_encode(['error' => 'Vérification anti-robot échouée']); exit;
}

/* ── 1. Stocker le lead dans Supabase (table leads) ── */
$supaUrl = rtrim($_ENV['SUPABASE_URL'] ?? '', '/');
$supaKey = $_ENV['SUPABASE_SECRET'] ?? ($_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? '');
if ($supaUrl && $supaKey) {
    $ch = curl_init("$supaUrl/rest/v1/leads");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => 8,
        CURLOPT_HTTPHEADER => ["apikey: $supaKey", "Authorization: Bearer $supaKey", "Content-Type: application/json", "Prefer: return=minimal"],
        CURLOPT_POSTFIELDS => json_encode([
            'email' => $email, 'name' => $nom, 'company' => $entreprise,
            'source' => $source, 'message' => $message,
            'meta' => ['type_besoin' => $type, 'nb_bornes' => $bornes],
            'created_at' => date('c'),
        ], JSON_UNESCAPED_UNICODE),
    ]);
    curl_exec($ch); curl_close($ch);
}

/* ── 2. Email de notification à l'équipe via Brevo ── */
$brevoKey = $_ENV['BREVO_API_KEY'] ?? '';
$fromEmail = $_ENV['BREVO_FROM_EMAIL'] ?? 'jean-mael.debain@sparklin.io';
$fromName  = $_ENV['BREVO_FROM_NAME']  ?? 'Sparklin';
$toEmail   = $_ENV['CONTACT_TO_EMAIL'] ?? 'contact@sparklin.io';

if (!$brevoKey) { http_response_code(500); echo json_encode(['error' => 'Brevo non configuré']); exit; }

$html = "<h2>Nouvelle demande de contact</h2>"
      . "<p><strong>Nom :</strong> " . ($nom ?: '—') . "</p>"
      . "<p><strong>Email :</strong> $email</p>"
      . "<p><strong>Entreprise :</strong> " . ($entreprise ?: '—') . "</p>"
      . "<p><strong>Type de besoin :</strong> " . ($type ?: '—') . "</p>"
      . "<p><strong>Nombre de bornes :</strong> " . ($bornes ?: '—') . "</p>"
      . "<p><strong>Message :</strong><br>" . nl2br($message ?: '—') . "</p>";

$payload = [
    'sender'  => ['name' => $fromName, 'email' => $fromEmail],
    'to'      => [['email' => $toEmail]],
    'replyTo' => ['email' => $email, 'name' => $nom ?: $email],
    'subject' => 'Contact site' . ($entreprise ? " — $entreprise" : '') . ($nom ? " ($nom)" : ''),
    'htmlContent' => $html,
];

$ch = curl_init('https://api.brevo.com/v3/smtp/email');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => 12,
    CURLOPT_HTTPHEADER => ["api-key: $brevoKey", "Content-Type: application/json", "accept: application/json"],
    CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code >= 200 && $code < 300) {
    // Inscription à la liste Brevo "Contact & Support" (best-effort)
    brevo_add_contact($email, $_ENV['BREVO_LIST_CONTACT_ID'] ?? 0, ['NOM' => $nom]);

    // Accusé de réception au visiteur (best-effort, via template Brevo)
    $ackTpl = intval($_ENV['BREVO_TEMPLATE_CONTACT_ACK_ID'] ?? 0);
    if ($ackTpl) {
        $ack = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ack, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ["api-key: $brevoKey", "Content-Type: application/json", "accept: application/json"],
            CURLOPT_POSTFIELDS => json_encode([
                'templateId' => $ackTpl,
                'to' => [['email' => $email, 'name' => $nom ?: $email]],
                'params' => ['NAME' => $nom],
            ], JSON_UNESCAPED_UNICODE),
        ]);
        curl_exec($ack); curl_close($ack);
    }

    echo json_encode(['ok' => true]);
} else {
    http_response_code(502);
    echo json_encode(['error' => 'Envoi échoué', 'brevo_code' => $code]);
}
