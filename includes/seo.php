<?php
/**
 * SEO helpers — architecture multilingue "?lang= indexable"
 * Génère : canonical auto-référent par langue, hreflang (7 langues + x-default),
 * Open Graph / Twitter, et noindex automatique hors production.
 *
 * Usage dans le <head> de chaque page :
 *   <?php require_once $_SERVER['DOCUMENT_ROOT'].'/includes/seo.php'; sk_seo_head(); ?>
 * Options : sk_seo_head(['image' => '...', 'type' => 'article']);
 */
if (!function_exists('sk_seo_head')) {

    // Domaine de production canonique (les canonicals pointent toujours ici,
    // même servi depuis la preprod → évite le duplicate content).
    function sk_seo_base(): string {
        return 'https://sparklin.io';
    }

    // Chemin courant sans query string (ex. /spark-pilot/).
    function sk_seo_path(): string {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        return ($path === null || $path === '') ? '/' : $path;
    }

    // URL absolue d'une langue donnée (fr = sans paramètre).
    function sk_seo_url(string $lang): string {
        $u = sk_seo_base() . sk_seo_path();
        return $lang === 'fr' ? $u : $u . '?lang=' . $lang;
    }

    function sk_seo_head(array $opts = []): void {
        $langs = ['fr','en','de','es','th','ms','id'];
        $cur   = function_exists('lang') ? lang() : 'fr';
        $img   = $opts['image'] ?? (sk_seo_base() . '/assets/images/dashboard-imac.jpg');
        $type  = $opts['type'] ?? 'website';
        $esc   = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

        // noindex hors production (preprod debaincorp, localhost…) ou si demandé (ex. page merci)
        $host = $_SERVER['HTTP_HOST'] ?? '';
        if (!empty($opts['noindex']) || stripos($host, 'sparklin.io') === false) {
            echo "  <meta name=\"robots\" content=\"noindex, nofollow\"/>\n";
        }

        // Canonical auto-référent (par langue → chaque langue est indexable)
        echo '  <link rel="canonical" href="' . $esc(sk_seo_url($cur)) . "\"/>\n";

        // hreflang : une alternance par langue + x-default (fr)
        foreach ($langs as $l) {
            echo '  <link rel="alternate" hreflang="' . $l . '" href="' . $esc(sk_seo_url($l)) . "\"/>\n";
        }
        echo '  <link rel="alternate" hreflang="x-default" href="' . $esc(sk_seo_url('fr')) . "\"/>\n";

        // Open Graph / Twitter
        $loc = ['fr'=>'fr_FR','en'=>'en_US','de'=>'de_DE','es'=>'es_ES','th'=>'th_TH','ms'=>'ms_MY','id'=>'id_ID'];
        echo '  <meta property="og:type" content="' . $esc($type) . "\"/>\n";
        echo '  <meta property="og:site_name" content="Sparklin"/>' . "\n";
        if (!empty($opts['title'])) {
            echo '  <meta property="og:title" content="' . $esc($opts['title']) . "\"/>\n";
            echo '  <meta name="twitter:title" content="' . $esc($opts['title']) . "\"/>\n";
        }
        if (!empty($opts['desc'])) {
            echo '  <meta property="og:description" content="' . $esc($opts['desc']) . "\"/>\n";
            echo '  <meta name="twitter:description" content="' . $esc($opts['desc']) . "\"/>\n";
        }
        echo '  <meta property="og:url" content="' . $esc(sk_seo_url($cur)) . "\"/>\n";
        echo '  <meta property="og:image" content="' . $esc($img) . "\"/>\n";
        echo '  <meta property="og:locale" content="' . ($loc[$cur] ?? 'fr_FR') . "\"/>\n";
        foreach ($loc as $l => $lc) {
            if ($l !== $cur) echo '  <meta property="og:locale:alternate" content="' . $lc . "\"/>\n";
        }
        echo '  <meta name="twitter:card" content="summary_large_image"/>' . "\n";
        echo '  <meta name="twitter:image" content="' . $esc($img) . "\"/>\n";
    }
}
