<?php
/**
 * Helper Brevo — ajoute/maj un contact dans une liste (best-effort).
 * Si des attributs personnalisés n'existent pas (400), on réessaie sans attributs
 * pour garantir l'inscription à la liste.
 */
if (!function_exists('brevo_add_contact')) {
    function brevo_add_contact(string $email, $listId, array $attributes = []): bool {
        $key    = $_ENV['BREVO_API_KEY'] ?? '';
        $listId = (int) $listId;
        if (!$key || !$listId || !$email) return false;

        $post = function (array $payload) use ($key) {
            $ch = curl_init('https://api.brevo.com/v3/contacts');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_TIMEOUT => 10,
                CURLOPT_HTTPHEADER => ["api-key: $key", "Content-Type: application/json", "accept: application/json"],
                CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
            ]);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $code;
        };

        $base = ['email' => $email, 'listIds' => [$listId], 'updateEnabled' => true];
        $attributes = array_filter($attributes, fn($v) => $v !== '' && $v !== null);
        $code = $post($attributes ? $base + ['attributes' => $attributes] : $base);
        if ($code >= 200 && $code < 300) return true;
        // attribut inconnu, etc. → on retente sans attributs (l'inscription à la liste prime)
        if ($attributes) { $code = $post($base); return $code >= 200 && $code < 300; }
        return false;
    }
}
