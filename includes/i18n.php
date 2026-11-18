<?php
/**
 * i18n helper — language detection + translation functions
 */

$GLOBALS['_i18n'] = [];
$GLOBALS['_lang'] = 'fr';

function initI18n(): string {
    $allowed = ['fr', 'en', 'de', 'es', 'th', 'ms', 'id', 'tr', 'vi', 'fil'];
    $lang = 'fr';

    // Priority: ?lang= > cookie > default
    if (isset($_GET['lang']) && in_array($_GET['lang'], $allowed)) {
        $lang = $_GET['lang'];
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (int)($_SERVER['SERVER_PORT'] ?? 80) === 443;
        setcookie('sk_lang', $lang, time() + 365*24*3600, '/', '', $isSecure, false);
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

/**
 * sk_format_date('2026-10-28T…') — date longue dans la langue courante
 * (mois écrits en toutes lettres ; pas d'extension intl requise au build).
 */
function sk_format_date(?string $iso, ?string $lang = null): string {
    if (!$iso) return '';
    $ts = strtotime($iso);
    if (!$ts) return '';
    $lang = $lang ?? lang();
    $M = [
        'fr'  => ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'],
        'en'  => ['January','February','March','April','May','June','July','August','September','October','November','December'],
        'de'  => ['Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
        'es'  => ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'],
        'th'  => ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
        'ms'  => ['Januari','Februari','Mac','April','Mei','Jun','Julai','Ogos','September','Oktober','November','Disember'],
        'id'  => ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
        'tr'  => ['Ocak','Şubat','Mart','Nisan','Mayıs','Haziran','Temmuz','Ağustos','Eylül','Ekim','Kasım','Aralık'],
        'vi'  => ['tháng 1','tháng 2','tháng 3','tháng 4','tháng 5','tháng 6','tháng 7','tháng 8','tháng 9','tháng 10','tháng 11','tháng 12'],
        'fil' => ['Enero','Pebrero','Marso','Abril','Mayo','Hunyo','Hulyo','Agosto','Setyembre','Oktubre','Nobyembre','Disyembre'],
    ];
    $months = $M[$lang] ?? $M['fr'];
    $d = (int)date('j', $ts); $m = $months[(int)date('n', $ts) - 1]; $y = date('Y', $ts);
    if ($lang === 'en') return "$m $d, $y";
    if ($lang === 'vi') return "$d $m, $y";
    return "$d $m $y";
}
