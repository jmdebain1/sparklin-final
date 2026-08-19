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
        // Google Ads (gtag.js) — même point d'entrée commun que GTM ci-dessous,
        // volontairement absent de l'admin.
        echo "  <!-- Google tag (gtag.js) -->\n";
        echo "  <script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-18111265049\"></script>\n";
        echo "  <script>\n";
        echo "    window.dataLayer = window.dataLayer || [];\n";
        echo "    function gtag(){dataLayer.push(arguments);}\n";
        echo "    gtag('js', new Date());\n";
        echo "\n";
        echo "    gtag('config', 'AW-18111265049');\n";
        echo "  </script>\n";

        // Google Tag Manager — présent sur toutes les pages publiques via ce
        // point d'entrée commun (sk_seo_head() est appelé dans le <head> de
        // chaque page). Volontairement absent de l'admin (admin-blog/*.php
        // n'appelle pas sk_seo_head) : pas de tracking visiteur sur l'outil interne.
        echo "  <!-- Google Tag Manager -->\n";
        echo "  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':\n";
        echo "  new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\n";
        echo "  j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n";
        echo "  'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n";
        echo "  })(window,document,'script','dataLayer','GTM-MLZH6M32');</script>\n";
        echo "  <!-- End Google Tag Manager -->\n";

        $langs = ['fr','en','de','es','th','ms','id'];
        $cur   = function_exists('lang') ? lang() : 'fr';
        $img   = $opts['image'] ?? (sk_seo_base() . '/assets/images/dashboard-imac.jpg');
        $type  = $opts['type'] ?? 'website';
        $esc   = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

        // noindex hors production (preprod, localhost…) ou si demandé (ex. page merci).
        // En pré-rendu statique (build Netlify), le Host vu par PHP est localhost —
        // SK_STATIC_BUILD permet de forcer l'indexabilité indépendamment de ce Host.
        $isStaticBuild = ($_ENV['SK_STATIC_BUILD'] ?? getenv('SK_STATIC_BUILD')) === '1';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $offProd = $isStaticBuild ? false : (stripos($host, 'sparklin.io') === false);
        if (!empty($opts['noindex']) || $offProd) {
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
