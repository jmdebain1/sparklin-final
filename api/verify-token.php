<?php
/* ══════════════════════════════════════════════════════════════
   /api/verify-token.php
   Endpoint vérification token admin
   Vérifie le token admin contre Supabase.
   ══════════════════════════════════════════════════════════════ */
require_once __DIR__ . '/../includes/env.php';
loadEnv(__DIR__ . '/../.env');

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit; }

$body = json_decode(file_get_contents('php://input'), true);
$token = $body['token'] ?? '';
if (!$token) { http_response_code(400); echo json_encode(['valid'=>false]); exit; }

// TODO: implement token verification with Supabase
// 1. Hash the token: hash('sha256', $token)
// 2. Query Supabase admin_sessions for matching hash + not expired
// 3. Return valid/invalid
echo json_encode(['valid' => false, 'message' => 'Not yet implemented']);
