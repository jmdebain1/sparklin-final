<?php
/* ══════════════════════════════════════════════════════════════
   /api/send-livre-blanc.php
   Endpoint formulaire livre blanc
   Reçoit le formulaire livre blanc, stocke le lead en Supabase,
   envoie l'email via Brevo.
   ══════════════════════════════════════════════════════════════ */
require_once __DIR__ . '/../includes/env.php';
loadEnv(__DIR__ . '/../.env');

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }

$body = json_decode(file_get_contents('php://input'), true);
$email = filter_var($body['email'] ?? '', FILTER_VALIDATE_EMAIL);
$name  = htmlspecialchars($body['name'] ?? '', ENT_QUOTES, 'UTF-8');
$company = htmlspecialchars($body['company'] ?? '', ENT_QUOTES, 'UTF-8');

if (!$email) { http_response_code(400); echo json_encode(['error'=>'Email invalide']); exit; }

/* ── 1. Store lead in Supabase ── */
$supaUrl = rtrim($_ENV['SUPABASE_URL'] ?? '', '/');
$supaKey = $_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? '';

if ($supaUrl && $supaKey) {
    $ch = curl_init("$supaUrl/rest/v1/leads");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "apikey: $supaKey",
            "Authorization: Bearer $supaKey",
            "Content-Type: application/json",
            "Prefer: return=minimal",
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'email'   => $email,
            'name'    => $name,
            'company' => $company,
            'source'  => 'livre-blanc',
            'created_at' => date('c'),
        ]),
    ]);
    curl_exec($ch);
    curl_close($ch);
}

/* ── 2. Send email via Brevo ── */
$brevoKey = $_ENV['BREVO_API_KEY'] ?? '';
$templateId = intval($_ENV['BREVO_TEMPLATE_LIVREBLANC_ID'] ?? 0);
$pdfUrl = $_ENV['LIVREBLANC_PDF_URL'] ?? 'https://sparklin.io/assets/livre-blanc-sparklin-2026.pdf';

if ($brevoKey) {
    $ch = curl_init('https://api.brevo.com/v3/smtp/email');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "api-key: $brevoKey",
            "Content-Type: application/json",
        ],
        CURLOPT_POSTFIELDS => json_encode($templateId ? [
            'templateId' => $templateId,
            'to' => [['email' => $email, 'name' => $name]],
            'params' => ['NAME' => $name, 'COMPANY' => $company, 'PDF_URL' => $pdfUrl],
        ] : [
            'sender' => ['name' => $_ENV['BREVO_FROM_NAME'] ?? 'Sparklin', 'email' => $_ENV['BREVO_FROM_EMAIL'] ?? 'contact@sparklin.io'],
            'to' => [['email' => $email, 'name' => $name]],
            'subject' => 'Votre guide Sparklin — Bornes de recharge en entreprise 2026',
            'htmlContent' => "<p>Bonjour $name,</p><p>Voici votre exemplaire du guide complet de la recharge électrique en entreprise.</p><p><a href=\"$pdfUrl\">Télécharger le PDF</a></p><p>L\'équipe Sparklin</p>",
        ]),
    ]);
    curl_exec($ch);
    curl_close($ch);
}

echo json_encode(['ok' => true]);
