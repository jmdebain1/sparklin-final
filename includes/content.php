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

function getPublishedPosts(): array {
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

function getPostBySlug(string $slug): ?array {
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
