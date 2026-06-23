<?php
/* ══════════════════════════════════════════════════════════════
   /api/send-magic-link.php
   Endpoint magic link admin
   Génère un token, le stocke en Supabase, envoie le lien par Brevo.
   ══════════════════════════════════════════════════════════════ */
require_once __DIR__ . '/../includes/env.php';
loadEnv(__DIR__ . '/../.env');

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$body = json_decode(file_get_contents('php://input'), true);
$email = filter_var($body['email'] ?? '', FILTER_VALIDATE_EMAIL);
if (!$email) { http_response_code(400); echo json_encode(['error'=>'Email invalide']); exit; }

// TODO: implement magic link logic with Supabase + Brevo
// 1. Generate token: bin2hex(random_bytes(32))
// 2. Hash it: hash('sha256', $token)
// 3. Store hash in Supabase admin_sessions table
// 4. Send email via Brevo with link: /admin-blog/login.php?token=$token
echo json_encode(['ok' => true, 'message' => 'Magic link sent']);
