<?php
/* ══════════════════════════════════════════════════════════════
   /api/send-magic-link.php
   Auth admin par lien magique.
   - vérifie que l'email est dans la liste blanche (admin_allowed_emails)
   - génère un token à usage unique, stocke son hash dans admin_sessions
   - envoie le lien par email via Brevo
   Réponse toujours "ok" (ne révèle pas si l'email est autorisé).
   ══════════════════════════════════════════════════════════════ */
require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/../includes/recaptcha.php';
loadEnv(__DIR__ . '/../.env');

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error' => 'Method not allowed']); exit; }

$body  = json_decode(file_get_contents('php://input'), true) ?: [];
$email = filter_var(strtolower(trim($body['email'] ?? '')), FILTER_VALIDATE_EMAIL);
if (!$email) { http_response_code(400); echo json_encode(['error' => 'Email invalide']); exit; }

// Anti-abus reCAPTCHA v3 (protège la connexion admin)
if (!recaptcha_verify($body['recaptcha_token'] ?? null, 'login')) {
    http_response_code(429); echo json_encode(['error' => 'Vérification anti-robot échouée']); exit;
}

$supaUrl  = rtrim($_ENV['SUPABASE_URL'] ?? '', '/');
$supaKey  = $_ENV['SUPABASE_SECRET'] ?? ($_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? '');
$brevoKey = $_ENV['BREVO_API_KEY'] ?? '';

function supa_req(string $method, string $url, string $key, ?array $payload = null): array {
    $ch = curl_init($url);
    $opts = [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method, CURLOPT_TIMEOUT => 8,
        CURLOPT_HTTPHEADER => ["apikey: $key", "Authorization: Bearer $key", "Content-Type: application/json", "Prefer: return=representation"],
    ];
    if ($payload !== null) $opts[CURLOPT_POSTFIELDS] = json_encode($payload, JSON_UNESCAPED_UNICODE);
    curl_setopt_array($ch, $opts);
    $resp = curl_exec($ch); $code = curl_getinfo($ch, CURLINFO_HTTP_CODE); curl_close($ch);
    return [$code, json_decode($resp, true)];
}

// Réponse générique (on ne révèle jamais si l'email est autorisé ou non)
$genericOk = function () { echo json_encode(['ok' => true]); exit; };

if (!$supaUrl || !$supaKey || !$brevoKey) { $genericOk(); }

// 1. Email autorisé ?
[$c, $rows] = supa_req('GET', "$supaUrl/rest/v1/admin_allowed_emails?email=eq." . urlencode($email) . "&select=email", $supaKey);
if ($c !== 200 || empty($rows)) { $genericOk(); }

// 2. Génère + stocke le token (hash), expire dans 15 min
$token = bin2hex(random_bytes(32));
$hash  = hash('sha256', $token);
supa_req('POST', "$supaUrl/rest/v1/admin_sessions", $supaKey, [
    'email' => $email,
    'token_hash' => $hash,
    'expires_at' => gmdate('c', time() + 15 * 60),
    'used' => false,
]);

// 3. Envoie le lien par Brevo
$host = $_SERVER['HTTP_HOST'] ?? 'www.sparklin.debaincorp.com';
$link = "https://$host/admin-blog/?token=" . $token;
$from = $_ENV['BREVO_FROM_EMAIL'] ?? 'jean-mael.debain@sparklin.io';
$linkEsc = htmlspecialchars($link, ENT_QUOTES);
$html = '<div style="font-family:Arial,sans-serif;max-width:480px;margin:auto;padding:24px;">'
      . '<h2 style="color:#1a1a2e;">Connexion — Espace Rédaction Sparklin</h2>'
      . '<p style="color:#444;line-height:1.6;">Cliquez sur le bouton ci-dessous pour vous connecter. Ce lien expire dans 15 minutes et ne peut servir qu\'une fois.</p>'
      . '<p style="text-align:center;margin:28px 0;"><a href="' . $linkEsc . '" style="display:inline-block;background:#E8563A;color:#fff;text-decoration:none;font-weight:700;padding:14px 28px;border-radius:10px;">Se connecter</a></p>'
      . '<p style="color:#999;font-size:12px;word-break:break-all;">Ou copiez ce lien : ' . $linkEsc . '</p>'
      . '<p style="color:#999;font-size:12px;">Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet email.</p></div>';

$ch = curl_init('https://api.brevo.com/v3/smtp/email');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => 12,
    CURLOPT_HTTPHEADER => ["api-key: $brevoKey", "Content-Type: application/json", "accept: application/json"],
    CURLOPT_POSTFIELDS => json_encode([
        'sender'  => ['name' => $_ENV['BREVO_FROM_NAME'] ?? 'Sparklin', 'email' => $from],
        'to'      => [['email' => $email]],
        'subject' => 'Votre lien de connexion — Sparklin',
        'htmlContent' => $html,
    ], JSON_UNESCAPED_UNICODE),
]);
curl_exec($ch); curl_close($ch);

$genericOk();
