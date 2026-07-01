<?php
/**
 * Vérification reCAPTCHA v3 (score).
 * - Si aucune clé secrète configurée → renvoie true (fail-open, ne bloque pas).
 * - Sinon : token requis, success requis, action vérifiée si fournie,
 *   et score >= seuil (RECAPTCHA_MIN_SCORE, défaut 0.6).
 */
if (!function_exists('recaptcha_verify')) {
    function recaptcha_verify(?string $token, string $expectedAction = ''): bool {
        $secret = $_ENV['RECAPTCHA_SECRET_KEY'] ?? '';
        if ($secret === '') return true; // non configuré → on ne bloque pas
        if (!$token) return false;

        $min = (float)($_ENV['RECAPTCHA_MIN_SCORE'] ?? 0.6);

        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => 8,
            CURLOPT_POSTFIELDS => http_build_query([
                'secret'   => $secret,
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
            ]),
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);
        $data = json_decode((string)$resp, true);

        if (empty($data['success'])) return false;
        if ($expectedAction !== '' && ($data['action'] ?? '') !== $expectedAction) return false;
        return ((float)($data['score'] ?? 0)) >= $min;
    }
}
