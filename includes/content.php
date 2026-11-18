<?php
/**
 * Contenu dynamique public (événements, presse) — lecture Supabase
 * avec cache fichier (5 min), même principe que getTranslations().
 */

function getEvents(string $status = ''): array {
    $cacheKey  = 'events' . ($status ? "_$status" : '');
    $cacheDir  = sys_get_temp_dir() . '/sparklin_content';
    $cacheFile = "$cacheDir/$cacheKey.json";
    $ttl       = 300;

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (is_array($data)) return $data;
    }

    $query = 'select=*&order=sort_order.asc,id.desc';
    if ($status) $query .= '&status=eq.' . urlencode($status);
    $rows = supabaseGet('events', $query) ?? [];

    @mkdir($cacheDir, 0755, true);
    file_put_contents($cacheFile, json_encode($rows, JSON_UNESCAPED_UNICODE));
    return $rows;
}

function getPressMentions(): array {
    $cacheDir  = sys_get_temp_dir() . '/sparklin_content';
    $cacheFile = "$cacheDir/press.json";
    $ttl       = 300;

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (is_array($data)) return $data;
    }

    $rows = supabaseGet('press_mentions', 'select=*&order=sort_order.asc,id.desc') ?? [];

    @mkdir($cacheDir, 0755, true);
    file_put_contents($cacheFile, json_encode($rows, JSON_UNESCAPED_UNICODE));
    return $rows;
}

// Traductions d'articles (table post_translations) : une ligne par (post_id, lang).
// Un champ vide ou absent retombe sur le français du post d'origine.
function getPostTranslations(string $lang): array {
    if ($lang === 'fr') return [];
    $cacheDir  = sys_get_temp_dir() . '/sparklin_content';
    $cacheFile = "$cacheDir/post_translations_" . preg_replace('/[^a-z]/', '', $lang) . ".json";
    $ttl       = 300;

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (is_array($data)) return $data;
    }

    $rows = supabaseGet('post_translations', 'select=*&lang=eq.' . urlencode($lang)) ?? [];
    $byId = [];
    foreach ($rows as $r) $byId[(string)$r['post_id']] = $r;

    @mkdir($cacheDir, 0755, true);
    file_put_contents($cacheFile, json_encode($byId, JSON_UNESCAPED_UNICODE));
    return $byId;
}

function sk_apply_post_translation(array $post, string $lang): array {
    $tr = getPostTranslations($lang)[(string)$post['id']] ?? null;
    if (!$tr) return $post;
    foreach (['title','excerpt','category','meta_title','meta_desc','hero_image_alt','read_time','body_html'] as $f) {
        if (isset($tr[$f]) && trim((string)$tr[$f]) !== '') $post[$f] = $tr[$f];
    }
    return $post;
}

function getPublishedPosts(?string $lang = null): array {
    $lang = $lang ?? (function_exists('lang') ? lang() : 'fr');
    return array_map(fn($p) => sk_apply_post_translation($p, $lang), getPublishedPostsFr());
}

function getPublishedPostsFr(): array {
    $cacheDir  = sys_get_temp_dir() . '/sparklin_content';
    $cacheFile = "$cacheDir/posts_published.json";
    $ttl       = 300;

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        if (is_array($data)) return $data;
    }

    $rows = supabaseGet('posts', 'select=*&status=eq.published&order=sort_order.asc,published_at.desc') ?? [];

    @mkdir($cacheDir, 0755, true);
    file_put_contents($cacheFile, json_encode($rows, JSON_UNESCAPED_UNICODE));
    return $rows;
}

function getPostBySlug(string $slug, ?string $lang = null): ?array {
    $lang = $lang ?? (function_exists('lang') ? lang() : 'fr');
    $post = getPostBySlugFr($slug);
    return $post ? sk_apply_post_translation($post, $lang) : null;
}

function getPostBySlugFr(string $slug): ?array {
    $cacheDir  = sys_get_temp_dir() . '/sparklin_content';
    $cacheFile = "$cacheDir/post_" . preg_replace('/[^a-z0-9\-]/', '', $slug) . ".json";
    $ttl       = 300;

    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $ttl) {
        $data = json_decode(file_get_contents($cacheFile), true);
        return $data ?: null;
    }

    $rows = supabaseGet('posts', 'select=*&slug=eq.' . urlencode($slug) . '&limit=1') ?? [];
    $post = $rows[0] ?? null;

    @mkdir($cacheDir, 0755, true);
    file_put_contents($cacheFile, json_encode($post, JSON_UNESCAPED_UNICODE));
    return $post;
}
