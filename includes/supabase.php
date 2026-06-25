<?php
/**
 * Supabase REST client — fetches i18n translations
 * Uses anon key (public, RLS-protected)
 */

function supabaseGet(string $table, string $query = ''): ?array {
    $url  = rtrim($_ENV['SUPABASE_URL'] ?? '', '/');
    $key  = $_ENV['SUPABASE_ANON_KEY'] ?? '';

    if (!$url || !$key) return null;

    $endpoint = "$url/rest/v1/$table" . ($query ? "?$query" : '');

    $ch = curl_init($endpoint);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            "apikey: $key",
            "Authorization: Bearer $key",
            "Content-Type: application/json",
        ],
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_CONNECTTIMEOUT => 3,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // curl_close() is a no-op and deprecated since PHP 8.0 — the handle is freed automatically.

    if ($httpCode !== 200 || !$response) return null;

    return json_decode($response, true);
}

/**
 * Fetch all translations for a given language
 * Returns associative array: ['nav.cta' => 'Get a quote', ...]
 */
function fetchTranslations(string $lang = 'fr'): array {
    // Map lang code to DB column
    $colMap = [
        'fr' => 'fr', 'en' => 'en', 'de' => 'de',
        'es' => 'es', 'th' => 'th', 'ms' => 'ms', 'id' => 'id_lang',
    ];
    $col = $colMap[$lang] ?? 'fr';
    $fallback = 'fr';

    // Fetch key + target lang + FR fallback, paginating to bypass the
    // PostgREST 1000-row response cap (the i18n table exceeds 1000 rows).
    $select   = urlencode("key,$col,$fallback");
    $pageSize = 1000;
    $offset   = 0;

    $translations = [];
    while (true) {
        $rows = supabaseGet('sparklin_i18n', "select=$select&order=key&limit=$pageSize&offset=$offset");
        if (!$rows) break;
        foreach ($rows as $row) {
            $key = $row['key'] ?? '';
            $val = $row[$col] ?? $row[$fallback] ?? '';
            if ($key && $val) {
                $translations[$key] = $val;
            }
        }
        if (count($rows) < $pageSize) break;
        $offset += $pageSize;
    }
    return $translations;
}

/**
 * Fetch translations with file cache (5 min TTL)
 * Avoids hitting Supabase on every page load
 */
function getTranslations(string $lang = 'fr'): array {
    $cacheDir  = sys_get_temp_dir() . '/sparklin_i18n';
    $cacheFile = "$cacheDir/$lang.json";
    $ttl       = 300; // 5 minutes

    // Return cached if fresh
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if ($data) return $data;
    }

    // Fetch from Supabase
    $data = fetchTranslations($lang);

    // Cache it
    if ($data) {
        @mkdir($cacheDir, 0755, true);
        file_put_contents($cacheFile, json_encode($data, JSON_UNESCAPED_UNICODE));
    }

    return $data;
}
