<?php
/* ══════════════════════════════════════════════════════════════
   /api/verify-token.php
   Vérifie un token magique (GET ?token=... ou POST {token}).
   Si valide + non expiré + non utilisé → le marque utilisé et
   renvoie une session { ok, sessionToken, email, expiresIn }.
   ══════════════════════════════════════════════════════════════ */
require_once __DIR__ . '/../includes/env.php';
loadEnv(__DIR__ . '/../.env');

header('Content-Type: application/json');

$token = $_GET['token'] ?? '';
if (!$token && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true) ?: [];
    $token = $body['token'] ?? '';
}
$token = trim((string)$token);
if ($token === '') { http_response_code(400); echo json_encode(['ok' => false]); exit; }

$supaUrl = rtrim($_ENV['SUPABASE_URL'] ?? '', '/');
$supaKey = $_ENV['SUPABASE_SECRET'] ?? ($_ENV['SUPABASE_SERVICE_ROLE_KEY'] ?? '');
if (!$supaUrl || !$supaKey) { http_response_code(500); echo json_encode(['ok' => false]); exit; }

$hash = hash('sha256', $token);
$now  = gmdate('c');

function supa(string $method, string $url, string $key, ?array $payload = null): array {
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

// 1. Cherche un token valide, non utilisé, non expiré
$q = "$supaUrl/rest/v1/admin_sessions?token_hash=eq." . urlencode($hash)
   . "&used=eq.false&expires_at=gt." . urlencode($now) . "&select=id,email&limit=1";
[$c, $rows] = supa('GET', $q, $supaKey);
if ($c !== 200 || empty($rows)) { http_response_code(401); echo json_encode(['ok' => false]); exit; }

$id    = $rows[0]['id'];
$email = $rows[0]['email'];

// 2. Marque le token comme utilisé (usage unique)
supa('PATCH', "$supaUrl/rest/v1/admin_sessions?id=eq." . urlencode($id), $supaKey, ['used' => true]);

// 3. Renvoie une session (7 jours)
echo json_encode([
    'ok'           => true,
    'sessionToken' => bin2hex(random_bytes(24)),
    'email'        => $email,
    'expiresIn'    => 7 * 24 * 3600,
]);
