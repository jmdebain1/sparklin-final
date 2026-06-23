<?php
/**
 * i18n helper — language detection + translation functions
 */

$GLOBALS['_i18n'] = [];
$GLOBALS['_lang'] = 'fr';

function initI18n(): string {
    $allowed = ['fr', 'en', 'de', 'es', 'th', 'ms', 'id'];
    $lang = 'fr';

    // Priority: ?lang= > cookie > default
    if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed)) {
        $lang = $_GET['lang'];
        setcookie('sk_lang', $lang, time() + 365*24*3600, '/', '', true, false);
    } elseif (isset($_COOKIE['sk_lang']) && in_array($_COOKIE['sk_lang'], $allowed)) {
        $lang = $_COOKIE['sk_lang'];
    }

    // Fetch translations from Supabase (with cache)
    $GLOBALS['_i18n'] = getTranslations($lang);
    $GLOBALS['_lang'] = $lang;

    // If Supabase is down or empty, fallback to fr
    if (empty($GLOBALS['_i18n']) && $lang !== 'fr') {
        $GLOBALS['_i18n'] = getTranslations('fr');
    }

    return $lang;
}

/**
 * t('key') — returns HTML-escaped translation
 */
function t(string $key): string {
    $val = $GLOBALS['_i18n'][$key] ?? $key;
    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

/**
 * tr('key') — returns raw translation (for HTML content: links, <em>, entities)
 */
function tr(string $key): string {
    return $GLOBALS['_i18n'][$key] ?? $key;
}

/**
 * lang() — returns current language code
 */
function lang(): string {
    return $GLOBALS['_lang'];
}
