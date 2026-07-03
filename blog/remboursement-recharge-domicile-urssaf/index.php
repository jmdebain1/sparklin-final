<?php
require_once __DIR__ . '/../../includes/env.php';
require_once __DIR__ . '/../../includes/supabase.php';
require_once __DIR__ . '/../../includes/i18n.php';
loadEnv(__DIR__ . '/../../.env');
$lang = initI18n();
?>
<!DOCTYPE html>
<html lang="<?= lang() ?>">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= tr('meta.blog_urssaf.title') ?></title>
  <meta name="description" content="<?= tr('meta.blog_urssaf.desc') ?>" data-i18n-meta="meta.blog_urssaf.desc"/>
  <link rel="icon" href="/favicon.ico" sizes="any"/>
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16.png"/>
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;500;600;700;800&family=Wix+Madefor+Text:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/assets/css/style.css">
  <meta name="keywords" content="load balancing IRVE, répartition puissance bornes recharge, Spark Pilot, déploiement IRVE"/>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "Load balancing : comment déployer 50 bornes sans renforcement EDF",
    "description": "Le load balancing dynamique permet de déployer massivement des bornes IRVE sans modifier votre contrat Enedis. Guide tec",
    "datePublished": "2025-09-15",
    "author": {"@type": "Organization", "name": "Sparklin", "url": "https://sparklin.io"},
    "publisher": {"@type": "Organization", "name": "Sparklin", "url": "https://sparklin.io"},
    "mainEntityOfPage": {"@type": "WebPage", "@id": "https://sparklin.io/blog/load-balancing-recharge-electrique/"}
  }
  </script>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "Remboursement recharge à domicile : le guide URSSAF complet 2026",
    "description": "Comment rembourser les frais de recharge domicile de vos salariés sans charges sociales. Conditions URSSAF 2026, certifi",
    "datePublished": "2025-11-10",
    "author": {"@type": "Organization", "name": "Sparklin", "url": "https://sparklin.io"},
    "publisher": {"@type": "Organization", "name": "Sparklin", "url": "https://sparklin.io"},
    "mainEntityOfPage": {"@type": "WebPage", "@id": "https://sparklin.io/blog/remboursement-recharge-domicile-urssaf/"}
  }
  </script>
  <?php require_once $_SERVER['DOCUMENT_ROOT'].'/includes/seo.php'; sk_seo_head(['title'=>tr('meta.blog_urssaf.title'),'desc'=>tr('meta.blog_urssaf.desc'),'type'=>'article']); ?>
</head>
<body>
<nav class="main-nav" style="position:fixed;top:0;left:0;right:0;z-index:200;display:flex;align-items:stretch;justify-content:space-between;background:rgba(255,255,255,.96);backdrop-filter:blur(16px);border-bottom:1px solid var(--border);padding:0 clamp(20px,5vw,80px);height:64px;">

  <!-- LOGO -->
  <div style="display:flex;align-items:center;flex-shrink:0;">
    <a href="/" style="display:flex;align-items:center;flex-shrink:0;"><svg style="height:52px;width:auto;display:block;" id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 242.5 78.07"><defs><style>.cls-1{fill:#ff6f49;}.cls-2{fill:#ed6e4f;}</style></defs><path class="cls-1" d="M182,13a4.74,4.74,0,1,0,4.74,4.74A4.74,4.74,0,0,0,182,13Z"/><path class="cls-2" d="M50.88,26.79h6.94L58,29.55A12.54,12.54,0,0,1,72.77,28c3.75,2.3,6,6.45,6,11.56a14.15,14.15,0,0,1-1.6,6.81,12.36,12.36,0,0,1-19,3.42V65.06H50.88Zm7.35,12.76c0,4,2.73,6.89,6.48,6.89s6.48-2.94,6.48-6.89-2.73-6.89-6.48-6.89S58.23,35.59,58.23,39.55Z"/><path class="cls-2" d="M83.26,32.76a12,12,0,0,1,10.8-6.43,11.79,11.79,0,0,1,8.36,3.27l.18-2.73h6.92V52.3h-6.13l-.53-2.73a13.94,13.94,0,0,1-8.8,3.24,11.9,11.9,0,0,1-6.38-1.71c-3.75-2.27-6.05-6.42-6.05-11.53A14.11,14.11,0,0,1,83.26,32.76ZM95.71,46.49c3.76,0,6.49-2.94,6.49-6.92s-2.73-6.86-6.49-6.86-6.48,3-6.48,6.86S92,46.49,95.71,46.49Zm6.46-5.16"/><path class="cls-2" d="M113.77,52.3V26.79h6.43l.35,3.65a8.76,8.76,0,0,1,7.66-4.16,12.78,12.78,0,0,1,3.42.51l-1,6.35a9.23,9.23,0,0,0-2.91-.48c-4,0-6.59,3-6.59,8.39V52.3Z"/><path class="cls-2" d="M134.8,52.3V14h7.35V37.25a43.48,43.48,0,0,1,3.7-4.75l5-5.71h8L149,38.25l11.64,14h-9l-7.3-8.62-2.22,2.6v6Z"/><path class="cls-2" d="M171.9,13V52.3h-7.35V13Z"/><path class="cls-2" d="M192.24,52.3V26.79h6.43L199,29.9a10.6,10.6,0,0,1,8.24-3.62c5.94,0,9.77,4.13,9.77,11.15V52.3h-7.38V38.86c0-4.14-1.55-6.2-4.64-6.2s-5.38,2.45-5.38,6.71V52.3Z"/><path class="cls-2" d="M25.49,49.37,29,43.76a32.43,32.43,0,0,0,4.1,2.34,8.12,8.12,0,0,0,3.35.87,3.06,3.06,0,0,0,1.84-.52A1.57,1.57,0,0,0,39,45.13a2.16,2.16,0,0,0-1.3-1.89,30.71,30.71,0,0,0-3-1.42c-1.21-.5-2.42-1.06-3.64-1.69A9.68,9.68,0,0,1,28,37.68a5.91,5.91,0,0,1-1.23-3.88,6.68,6.68,0,0,1,2.6-5.4c1.73-1.41,4.19-2.12,7.36-2.12a22.14,22.14,0,0,1,4.9.58,15.38,15.38,0,0,1,4.69,1.88L43,34.4a12.29,12.29,0,0,0-3-1.33A10.46,10.46,0,0,0,37,32.49a4.79,4.79,0,0,0-1.59.29,1.15,1.15,0,0,0-.87,1.15,1.82,1.82,0,0,0,1.2,1.58,28.88,28.88,0,0,0,2.86,1.27c1.19.46,2.41,1,3.67,1.64a9.89,9.89,0,0,1,3.2,2.51,6.18,6.18,0,0,1,1.3,4.1,7,7,0,0,1-1.39,4.37,8.89,8.89,0,0,1-3.73,2.8,13.15,13.15,0,0,1-5.14,1A17.86,17.86,0,0,1,31,52.24,18.87,18.87,0,0,1,25.49,49.37Z"/><path class="cls-2" d="M182,27.54a9.76,9.76,0,0,1-3.67-.73V52.3h7.35V26.81A9.78,9.78,0,0,1,182,27.54Z"/></svg></a>
  </div>

  <!-- MEGA NAV — all items in one flex container with align-items:stretch -->
  <div class="nav-mega" id="main-mega-nav" style="display:flex;align-items:center;height:100%;gap:0;">

    <!-- GÉRER VOS BORNES -->
    <div class="nav-mega-item" id="nav-gerer" style="position:relative;display:flex;align-items:center;">
      <button class="nav-mega-trigger" onclick="toggleMega('nav-gerer')" style="padding:0 16px;font-size:15px;font-weight:500;color:var(--text-mid);border:none;background:none;cursor:pointer;white-space:nowrap;display:flex;align-items:center;font-family:var(--font-body);border-bottom:2px solid transparent;transition:color .2s,border-color .2s;" data-i18n="nav.manage"><?= tr('nav.manage') ?></button>
      <div class="nav-mega-panel" style="display:none;position:absolute;top:100%;left:0;background:#fff;border:1px solid var(--border);border-radius:0 0 16px 16px;box-shadow:0 16px 48px rgba(26,26,46,.1);padding:16px;z-index:300;min-width:220px;flex-direction:column;gap:2px;">
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-light);padding:4px 10px 8px;display:block;"><?= t('nav.platform') ?></span>
        <a href="/spark-pilot/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);">Spark Pilot</div><div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.pilot_sub') ?></div></div>
        </a>
        <a href="/app/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);">Sparklin App</div><div style="font-size:12px;color:var(--text-light);margin-top:1px;">iOS &amp; Android</div></div>
        </a>
      </div>
    </div>

    <!-- NOS BORNES -->
    <div class="nav-mega-item" id="nav-bornes" style="position:relative;display:flex;align-items:center;">
      <button class="nav-mega-trigger" onclick="toggleMega('nav-bornes')" style="padding:0 16px;font-size:15px;font-weight:500;color:var(--text-mid);border:none;background:none;cursor:pointer;white-space:nowrap;display:flex;align-items:center;font-family:var(--font-body);border-bottom:2px solid transparent;transition:color .2s,border-color .2s;" data-i18n="nav.solutions"><?= tr('nav.solutions') ?></button>
      <div class="nav-mega-panel" style="display:none;position:absolute;top:100%;left:0;background:#fff;border:1px solid var(--border);border-radius:0 0 16px 16px;box-shadow:0 16px 48px rgba(26,26,46,.1);padding:16px;z-index:300;min-width:220px;flex-direction:column;gap:2px;">
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-light);padding:4px 10px 8px;display:block;"><?= t('nav.range') ?></span>
        <a href="/spark-1/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);">Spark 1</div><div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.s1_sub') ?></div></div>
        </a>
        <a href="/spark-plus/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);">Spark Plus</div><div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.splus_sub') ?></div></div>
        </a>
        <a href="/spark-go-e/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);">Spark x go-e</div><div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.goe_sub') ?></div></div>
        </a>
      </div>
    </div>

    <!-- CAS CLIENTS -->
    <div class="nav-mega-item" id="nav-cas" style="position:relative;display:flex;align-items:center;">
      <button class="nav-mega-trigger" onclick="toggleMega('nav-cas')" style="padding:0 16px;font-size:15px;font-weight:500;color:var(--text-mid);border:none;background:none;cursor:pointer;white-space:nowrap;display:flex;align-items:center;font-family:var(--font-body);border-bottom:2px solid transparent;transition:color .2s,border-color .2s;" data-i18n="nav.cases"><?= tr('nav.cases') ?></button>
      <div class="nav-mega-panel" style="display:none;position:absolute;top:100%;left:0;background:#fff;border:1px solid var(--border);border-radius:0 0 16px 16px;box-shadow:0 16px 48px rgba(26,26,46,.1);padding:16px;z-index:300;min-width:240px;flex-direction:column;gap:2px;">
        <a href="/cas/pme/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);" data-i18n="cas.pme.title"><?= tr('cas.pme.title') ?></div><div style="font-size:12px;color:var(--text-light);margin-top:1px;" data-i18n="cas.pme.sub"><?= tr('cas.pme.sub') ?></div></div>
        </a>
        <a href="/cas/collaborateurs/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);" data-i18n="cas.collab.title"><?= tr('cas.collab.title') ?></div><div style="font-size:12px;color:var(--text-light);margin-top:1px;" data-i18n="cas.collab.sub"><?= tr('cas.collab.sub') ?></div></div>
        </a>
        <a href="/cas/hotel/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);" data-i18n="cas.hotel.title"><?= tr('cas.hotel.title') ?></div><div style="font-size:12px;color:var(--text-light);margin-top:1px;" data-i18n="cas.hotel.sub"><?= tr('cas.hotel.sub') ?></div></div>
        </a>
        <a href="/cas/camping/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);"><?= t('footer.lnk.camping') ?></div><div style="font-size:12px;color:var(--text-light);margin-top:1px;" data-i18n="cas.camping.sub"><?= tr('cas.camping.sub') ?></div></div>
        </a>
        <a href="/cas/collectivite/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          <div><div style="font-size:14.5px;font-weight:500;color:var(--text-dark);" data-i18n="cas.coll.title"><?= tr('cas.coll.title') ?></div><div style="font-size:12px;color:var(--text-light);margin-top:1px;" data-i18n="cas.coll.sub"><?= tr('cas.coll.sub') ?></div></div>
        </a>
      </div>
    </div>

    <!-- RESSOURCES -->
    <div class="nav-mega-item" id="nav-ressources" style="position:relative;display:flex;align-items:center;">
      <button class="nav-mega-trigger" onclick="toggleMega('nav-ressources')" style="padding:0 16px;font-size:15px;font-weight:500;color:var(--text-mid);border:none;background:none;cursor:pointer;white-space:nowrap;display:flex;align-items:center;font-family:var(--font-body);border-bottom:2px solid transparent;transition:color .2s,border-color .2s;" data-i18n="nav.resources"><?= tr('nav.resources') ?></button>
      <div class="nav-mega-panel" style="display:none;flex-direction:column;position:absolute;top:100%;left:0;background:#fff;border:1px solid var(--border);border-radius:0 0 16px 16px;box-shadow:0 16px 48px rgba(26,26,46,.1);padding:8px;min-width:260px;z-index:500;">
        <a href="/blog/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          <div>
            <div style="font-size:14.5px;font-weight:500;color:var(--text-dark);"><?= t('blog.label') ?></div>
            <div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.d.blog.sub') ?></div>
          </div>
        </a>
        <a href="/livre-blanc/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div>
            <div style="font-size:14.5px;font-weight:500;color:var(--text-dark);"><?= t('footer.lnk.lb') ?> <span style="background:var(--orange);color:#fff;font-size:9px;font-weight:700;padding:1px 6px;border-radius:100px;vertical-align:middle;" data-i18n="footer.new"><?= tr('footer.new') ?></span></div>
            <div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.lb_sub') ?></div>
          </div>
        </a>
        <a href="/support/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div>
            <div style="font-size:14.5px;font-weight:500;color:var(--text-dark);"><?= t('footer.lnk.support') ?></div>
            <div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.d.support.sub') ?></div>
          </div>
        </a>
        <a href="/evenements/" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='var(--bg-off)'" onmouseout="this.style.background='transparent'">
          
          <div>
            <div style="font-size:14.5px;font-weight:500;color:var(--text-dark);"><?= t('footer.lnk.events') ?></div>
            <div style="font-size:12px;color:var(--text-light);margin-top:1px;"><?= t('nav.d.events.sub') ?></div>
          </div>
        </a>
      </div>
    </div>

    <!-- À PROPOS — intégré dans nav-mega comme les autres items -->
    <div class="nav-mega-item" style="position:relative;display:flex;align-items:center;">
      <a href="/a-propos/" data-i18n="nav.about" class="nav-desktop-only" style="padding:0 16px;font-size:15px;font-weight:500;color:var(--text-mid);text-decoration:none;white-space:nowrap;display:flex;align-items:center;font-family:var(--font-body);border-bottom:2px solid transparent;transition:color .2s,border-color .2s;"><?= t('nav.about') ?></a>
    </div>

  </div>

  <!-- RIGHT: CTA + LANG -->
  <div class="nav-desktop-only" style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
    <a href="/contact/" style="background:var(--orange);color:#fff;padding:10px 22px;border-radius:8px;font-size:14px;font-weight:700;text-decoration:none;white-space:nowrap;box-shadow:0 4px 16px rgba(232,86,58,.25);transition:background .2s;" onmouseover="this.style.background='var(--orange-light)'" onmouseout="this.style.background='var(--orange)'" data-i18n="nav.cta"><?= tr('nav.cta') ?></a>

    <!-- LANG DROPDOWN -->
    <div class="sk-lang-wrap" id="sk-lang-wrap" style="position:relative;flex-shrink:0;">
      <button class="sk-lang-trigger" id="sk-lang-trigger" onclick="toggleLangMenu(event)"
        aria-haspopup="listbox" aria-expanded="false"
        style="display:flex;align-items:center;gap:6px;padding:6px 10px;background:none;border:1.5px solid var(--border);border-radius:8px;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;color:var(--text-mid);transition:all .15s;white-space:nowrap;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        <span id="sk-lang-label"><?= strtoupper(lang()) ?></span>
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" id="sk-lang-caret" style="transition:transform .2s"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div class="sk-lang-menu" id="sk-lang-menu" role="listbox"
        style="display:none;position:absolute;top:calc(100% + 8px);right:0;background:#fff;border:1px solid var(--border);border-radius:10px;box-shadow:0 8px 32px rgba(26,26,46,.1);min-width:170px;padding:5px;z-index:999;overflow:hidden;">
        <a class="sk-lang-opt" data-lang="fr"<?= lang()==='fr'?' style="background:var(--bg-off);font-weight:700;color:var(--orange);border-radius:6px;"':'' ?> href="?lang=fr" title="Français"><span>Français</span></a>
        <a class="sk-lang-opt" data-lang="en"<?= lang()==='en'?' style="background:var(--bg-off);font-weight:700;color:var(--orange);border-radius:6px;"':'' ?> href="?lang=en" title="English"><span>English</span></a>
        <a class="sk-lang-opt" data-lang="de"<?= lang()==='de'?' style="background:var(--bg-off);font-weight:700;color:var(--orange);border-radius:6px;"':'' ?> href="?lang=de" title="Deutsch"><span>Deutsch</span></a>
        <a class="sk-lang-opt" data-lang="es"<?= lang()==='es'?' style="background:var(--bg-off);font-weight:700;color:var(--orange);border-radius:6px;"':'' ?> href="?lang=es" title="Español"><span>Español</span></a>
        <a class="sk-lang-opt" data-lang="th"<?= lang()==='th'?' style="background:var(--bg-off);font-weight:700;color:var(--orange);border-radius:6px;"':'' ?> href="?lang=th" title="ภาษาไทย"><span>ภาษาไทย</span></a>
        <a class="sk-lang-opt" data-lang="ms"<?= lang()==='ms'?' style="background:var(--bg-off);font-weight:700;color:var(--orange);border-radius:6px;"':'' ?> href="?lang=ms" title="Bahasa Melayu"><span>Bahasa Melayu</span></a>
        <a class="sk-lang-opt" data-lang="id"<?= lang()==='id'?' style="background:var(--bg-off);font-weight:700;color:var(--orange);border-radius:6px;"':'' ?> href="?lang=id" title="Bahasa Indonesia"><span>Bahasa Indonesia</span></a>
      </div>
    </div>
  </div>

  <!-- MOBILE HAMBURGER -->
  <button class="nav-hamburger" id="nav-hamburger" onclick="toggleMobileNav()" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>
<!-- MOBILE DRAWER -->
<div class="nav-mobile-drawer" id="nav-mobile-drawer">

  <div class="nav-drawer-section">
    <button class="nav-drawer-trigger" onclick="toggleDrawerSection(this)">
      <?= t('nav.manage') ?> <span class="arrow">›</span>
    </button>
    <div class="nav-drawer-links">
      <a href="/spark-pilot/"><?= t('nav.dr_pilot') ?></a>
      <a href="/app/"><?= t('nav.dr_app') ?></a>
    </div>
  </div>

  <div class="nav-drawer-section">
    <button class="nav-drawer-trigger" onclick="toggleDrawerSection(this)">
      <?= t('nav.solutions') ?> <span class="arrow">›</span>
    </button>
    <div class="nav-drawer-links">
      <a href="/spark-1/"><?= t('nav.dr_s1') ?></a>
      <a href="/spark-plus/"><?= t('nav.dr_splus') ?></a>
      <a href="/spark-go-e/"><?= t('nav.dr_goe') ?></a>
    </div>
  </div>

  <div class="nav-drawer-section">
    <button class="nav-drawer-trigger" onclick="toggleDrawerSection(this)">
      <?= t('nav.cases') ?> <span class="arrow">›</span>
    </button>
    <div class="nav-drawer-links">
      <a href="/cas/pme/"><?= t('nav.dr_pme') ?></a>
      <a href="/cas/collaborateurs/"><?= t('nav.dr_collab') ?></a>
      <a href="/cas/hotel/"><?= t('footer.lnk.hotel') ?></a>
      <a href="/cas/camping/"><?= t('footer.lnk.camping') ?></a>
      <a href="/cas/collectivite/"><?= t('nav.dr_coll') ?></a>
    </div>
  </div>

  <div class="nav-drawer-section">
    <button class="nav-drawer-trigger" onclick="toggleDrawerSection(this)">
      <?= t('nav.resources') ?> <span class="arrow">›</span>
    </button>
    <div class="nav-drawer-links">
      <a href="/blog/"><?= t('blog.label') ?></a>
      <a href="/livre-blanc/"><?= t('nav.dr_lb') ?></a>
      <a href="/support/"><?= t('nav.dr_support') ?></a>
      <a href="/evenements/"><?= t('footer.lnk.events') ?></a>
    </div>
  </div>

  <a class="nav-drawer-link" href="/a-propos/"><?= t('nav.about') ?></a>
  <div class="nav-drawer-section">
    <button class="nav-drawer-trigger" onclick="toggleDrawerSection(this)">
      <?= t('nav.language') ?> <span class="arrow">›</span>
    </button>
    <div class="nav-drawer-links">
      <a href="?lang=fr" data-lang="fr" class="nav-drawer-lang-opt<?= lang()==='fr'?' active':'' ?>"><span>🇫🇷</span><span>Français</span></a>
      <a href="?lang=en" data-lang="en" class="nav-drawer-lang-opt<?= lang()==='en'?' active':'' ?>"><span>🇬🇧</span><span>English</span></a>
      <a href="?lang=de" data-lang="de" class="nav-drawer-lang-opt<?= lang()==='de'?' active':'' ?>"><span>🇩🇪</span><span>Deutsch</span></a>
      <a href="?lang=es" data-lang="es" class="nav-drawer-lang-opt<?= lang()==='es'?' active':'' ?>"><span>🇪🇸</span><span>Español</span></a>
      <a href="?lang=th" data-lang="th" class="nav-drawer-lang-opt<?= lang()==='th'?' active':'' ?>"><span>🇹🇭</span><span>ภาษาไทย</span></a>
      <a href="?lang=ms" data-lang="ms" class="nav-drawer-lang-opt<?= lang()==='ms'?' active':'' ?>"><span>🇲🇾</span><span>Bahasa Melayu</span></a>
      <a href="?lang=id" data-lang="id" class="nav-drawer-lang-opt<?= lang()==='id'?' active':'' ?>"><span>🇮🇩</span><span>Bahasa Indonesia</span></a>
    </div>
  </div>


  <div class="nav-drawer-cta">
    <a href="/contact/" class="btn-primary"><?= t('nav.cta') ?> →</a>
  </div>

</div>
<main style="padding-top:64px;">
<div style="height:340px;background:linear-gradient(135deg,var(--dark) 0%,#2d2d48 100%);display:flex;align-items:flex-end;padding:40px clamp(20px,5vw,80px);position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background:rgba(0,0,0,.3);"></div>
  <div style="position:relative;z-index:1;font-size:80px;">⚡</div>
</div>
<article style="padding:60px clamp(20px,5vw,80px);">
  <div class="sk-wrap sk-wrap--narrow">
    <div style="font-size:12px;font-weight:600;color:var(--text-light);letter-spacing:.08em;text-transform:uppercase;margin-bottom:16px;" data-i18n="blog.urssaf.div1"><?= tr('blog.urssaf.div1') ?></div>
    
<h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,3vw,2.6rem);font-weight:800;color:var(--dark);line-height:1.2;margin-bottom:20px;" data-i18n="blog.urssaf.h11"><?= tr('blog.urssaf.h11') ?></h1>
<p style="font-size:17px;color:var(--text-mid);line-height:1.75;margin-bottom:40px;font-weight:300;" data-i18n="blog.urssaf.p1"><?= tr('blog.urssaf.p1') ?></p>

<nav style="background:var(--bg-off);border-radius:12px;padding:20px 24px;margin-bottom:40px;">
  <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-light);margin-bottom:12px;" data-i18n="blog.urssaf.div2"><?= tr('blog.urssaf.div2') ?></div>
  <ol style="margin:0;padding-left:20px;display:flex;flex-direction:column;gap:6px;">
    <li style="font-size:14px;" data-i18n="blog.urssaf.li1"><?= tr('blog.urssaf.li1') ?></li>
    <li style="font-size:14px;" data-i18n="blog.urssaf.li2"><?= tr('blog.urssaf.li2') ?></li>
    <li style="font-size:14px;" data-i18n="blog.urssaf.li3"><?= tr('blog.urssaf.li3') ?></li>
    <li style="font-size:14px;" data-i18n="blog.urssaf.li4"><?= tr('blog.urssaf.li4') ?></li>
    <li style="font-size:14px;" data-i18n="blog.urssaf.li5"><?= tr('blog.urssaf.li5') ?></li>
    <li style="font-size:14px;" data-i18n="blog.urssaf.li6"><?= tr('blog.urssaf.li6') ?></li>
    <li style="font-size:14px;" data-i18n="blog.urssaf.li7"><?= tr('blog.urssaf.li7') ?></li>
  </ol>
</nav>

<h2 id="principe" data-i18n="blog.urssaf.h21"><?= tr('blog.urssaf.h21') ?></h2>
<p data-i18n="blog.urssaf.p2"><?= tr('blog.urssaf.p2') ?></p>
<p data-i18n="blog.urssaf.p3"><?= tr('blog.urssaf.p3') ?></p>
<ul>
  <li data-i18n="blog.urssaf.li8"><?= tr('blog.urssaf.li8') ?></li>
  <li data-i18n="blog.urssaf.li9"><?= tr('blog.urssaf.li9') ?></li>
  <li data-i18n="blog.urssaf.li10"><?= tr('blog.urssaf.li10') ?></li>
  <li data-i18n="blog.urssaf.li11"><?= tr('blog.urssaf.li11') ?></li>
</ul>

<div style="background:rgba(232,86,58,.06);border-left:4px solid var(--orange);border-radius:0 8px 8px 0;padding:20px 24px;margin:32px 0;">
  <div style="font-weight:700;color:var(--dark);margin-bottom:6px;" data-i18n="blog.urssaf.div3"><?= tr('blog.urssaf.div3') ?></div>
  <p style="margin:0;color:var(--text-mid);font-size:14px;line-height:1.7;" data-i18n="blog.urssaf.p4"><?= tr('blog.urssaf.p4') ?></p>
</div>

<h2 id="conditions" data-i18n="blog.urssaf.h22"><?= tr('blog.urssaf.h22') ?></h2>
<p data-i18n="blog.urssaf.p5"><?= tr('blog.urssaf.p5') ?></p>

<h3 data-i18n="blog.urssaf.h31"><?= tr('blog.urssaf.h31') ?></h3>
<p data-i18n="blog.urssaf.p6"><?= tr('blog.urssaf.p6') ?></p>

<h3 data-i18n="blog.urssaf.h32"><?= tr('blog.urssaf.h32') ?></h3>
<p data-i18n="blog.urssaf.p7"><?= tr('blog.urssaf.p7') ?></p>

<h3 data-i18n="blog.urssaf.h33"><?= tr('blog.urssaf.h33') ?></h3>
<p data-i18n="blog.urssaf.p8"><?= tr('blog.urssaf.p8') ?></p>

<h2 id="certification-mid" data-i18n="blog.urssaf.h23"><?= tr('blog.urssaf.h23') ?></h2>
<p data-i18n="blog.urssaf.p9"><?= tr('blog.urssaf.p9') ?></p>
<p data-i18n="blog.urssaf.p10"><?= tr('blog.urssaf.p10') ?></p>
<ul>
  <li data-i18n="blog.urssaf.li12"><?= tr('blog.urssaf.li12') ?></li>
  <li data-i18n="blog.urssaf.li13"><?= tr('blog.urssaf.li13') ?></li>
  <li data-i18n="blog.urssaf.li14"><?= tr('blog.urssaf.li14') ?></li>
</ul>
<p data-i18n="blog.urssaf.p11"><?= tr('blog.urssaf.p11') ?></p>

<h2 id="calcul" data-i18n="blog.urssaf.h24"><?= tr('blog.urssaf.h24') ?></h2>
<p data-i18n="blog.urssaf.p12"><?= tr('blog.urssaf.p12') ?></p>
<p style="background:var(--bg-off);border-radius:8px;padding:16px 20px;font-family:monospace;font-size:14px;" data-i18n="blog.urssaf.p13"><?= tr('blog.urssaf.p13') ?></p>
<p data-i18n="blog.urssaf.p14"><?= tr('blog.urssaf.p14') ?></p>

<div style="overflow-x:auto;margin:32px 0;">
<table style="width:100%;border-collapse:collapse;font-size:14px;">
  <thead>
    <tr style="background:var(--dark);color:#fff;">
      <th style="padding:12px 16px;text-align:left;border-radius:8px 0 0 0;" data-i18n="blog.urssaf.th1"><?= tr('blog.urssaf.th1') ?></th>
      <th style="padding:12px 16px;text-align:right;border-radius:0 8px 0 0;" data-i18n="blog.urssaf.th2"><?= tr('blog.urssaf.th2') ?></th>
    </tr>
  </thead>
  <tbody>
    <tr style="border-bottom:1px solid var(--border);">
      <td style="padding:12px 16px;color:var(--dark);" data-i18n="blog.urssaf.td1"><?= tr('blog.urssaf.td1') ?></td>
      <td style="padding:12px 16px;text-align:right;font-weight:700;color:var(--orange);" data-i18n="blog.urssaf.td2"><?= tr('blog.urssaf.td2') ?></td>
    </tr>
    <tr style="border-bottom:1px solid var(--border);background:var(--bg-off);">
      <td style="padding:12px 16px;color:var(--dark);" data-i18n="blog.urssaf.td3"><?= tr('blog.urssaf.td3') ?></td>
      <td style="padding:12px 16px;text-align:right;font-weight:700;color:var(--orange);" data-i18n="blog.urssaf.td4"><?= tr('blog.urssaf.td4') ?></td>
    </tr>
    <tr>
      <td style="padding:12px 16px;color:var(--dark);" data-i18n="blog.urssaf.td5"><?= tr('blog.urssaf.td5') ?></td>
      <td style="padding:12px 16px;text-align:right;font-weight:700;color:var(--orange);" data-i18n="blog.urssaf.td6"><?= tr('blog.urssaf.td6') ?></td>
    </tr>
  </tbody>
</table>
</div>

<h2 id="avantage-nature" data-i18n="blog.urssaf.h25"><?= tr('blog.urssaf.h25') ?></h2>
<p data-i18n="blog.urssaf.p15"><?= tr('blog.urssaf.p15') ?></p>
<p data-i18n="blog.urssaf.p16"><?= tr('blog.urssaf.p16') ?></p>

<h2 id="sparklin-go-e" data-i18n="blog.urssaf.h26"><?= tr('blog.urssaf.h26') ?></h2>
<p data-i18n="blog.urssaf.p17"><?= tr('blog.urssaf.p17') ?></p>
<ol>
  <li data-i18n="blog.urssaf.li15"><?= tr('blog.urssaf.li15') ?></li>
  <li data-i18n="blog.urssaf.li16"><?= tr('blog.urssaf.li16') ?></li>
  <li data-i18n="blog.urssaf.li17"><?= tr('blog.urssaf.li17') ?></li>
  <li data-i18n="blog.urssaf.li18"><?= tr('blog.urssaf.li18') ?></li>
</ol>

<h2 id="economies" data-i18n="blog.urssaf.h27"><?= tr('blog.urssaf.h27') ?></h2>

<div style="overflow-x:auto;margin:32px 0;">
<table style="width:100%;border-collapse:collapse;font-size:14px;">
  <thead>
    <tr style="background:var(--dark);color:#fff;">
      <th style="padding:12px 16px;text-align:left;border-radius:8px 0 0 0;" data-i18n="blog.urssaf.th3"><?= tr('blog.urssaf.th3') ?></th>
      <th style="padding:12px 16px;text-align:right;" data-i18n="blog.urssaf.th4"><?= tr('blog.urssaf.th4') ?></th>
      <th style="padding:12px 16px;text-align:right;border-radius:0 8px 0 0;" data-i18n="blog.urssaf.th5"><?= tr('blog.urssaf.th5') ?></th>
    </tr>
  </thead>
  <tbody>
    <tr style="border-bottom:1px solid var(--border);">
      <td style="padding:12px 16px;color:var(--dark);font-weight:500;" data-i18n="blog.urssaf.td7"><?= tr('blog.urssaf.td7') ?></td>
      <td style="padding:12px 16px;text-align:right;color:#DC2626;" data-i18n="blog.urssaf.td8"><?= tr('blog.urssaf.td8') ?></td>
      <td style="padding:12px 16px;text-align:right;color:#16A34A;font-weight:600;" data-i18n="blog.urssaf.td9"><?= tr('blog.urssaf.td9') ?></td>
    </tr>
    <tr style="border-bottom:1px solid var(--border);background:var(--bg-off);">
      <td style="padding:12px 16px;color:var(--dark);font-weight:500;" data-i18n="blog.urssaf.td10"><?= tr('blog.urssaf.td10') ?></td>
      <td style="padding:12px 16px;text-align:right;color:#DC2626;" data-i18n="blog.urssaf.td11"><?= tr('blog.urssaf.td11') ?></td>
      <td style="padding:12px 16px;text-align:right;color:#16A34A;font-weight:600;" data-i18n="blog.urssaf.td12"><?= tr('blog.urssaf.td12') ?></td>
    </tr>
    <tr style="background:rgba(232,86,58,.06);">
      <td style="padding:12px 16px;color:var(--dark);font-weight:700;" data-i18n="blog.urssaf.td13"><?= tr('blog.urssaf.td13') ?></td>
      <td style="padding:12px 16px;text-align:right;color:var(--text-mid);">—</td>
      <td style="padding:12px 16px;text-align:right;color:var(--orange);font-weight:700;" data-i18n="blog.urssaf.td14"><?= tr('blog.urssaf.td14') ?></td>
    </tr>
  </tbody>
</table>
</div>

<div style="background:var(--dark);border-radius:16px;padding:32px;margin-top:40px;text-align:center;">
  <div style="font-family:var(--font-display);font-size:20px;font-weight:800;color:#fff;margin-bottom:8px;" data-i18n="blog.urssaf.div4"><?= tr('blog.urssaf.div4') ?></div>
  <p style="color:rgba(255,255,255,.6);font-size:14px;margin-bottom:20px;line-height:1.7;" data-i18n="blog.urssaf.p18"><?= tr('blog.urssaf.p18') ?></p>
  <a href="/contact/" style="display:inline-block;background:var(--orange);color:#fff;padding:14px 28px;border-radius:10px;font-weight:700;text-decoration:none;font-size:15px;" data-i18n="blog.urssaf.cta_btn"><?= tr('blog.urssaf.cta_btn') ?></a>
</div>

    <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--border);">
      <a href="/blog/" style="font-size:14px;color:var(--text-mid);text-decoration:none;font-weight:500;" data-i18n="blog.back"><?= tr('blog.back') ?></a>
    </div>
  </div>
</article>
</main>
<footer class="site-footer">
  <div class="footer-grid">

    <!-- BRAND -->
    <div class="footer-brand">
      <a href="/" style="display:inline-block;margin-bottom:20px;"><svg style="height:48px;width:auto;display:block;" id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 242.5 78.07"><defs><style>.cls-1{fill:#ff6f49;}.cls-2{fill:#ed6e4f;}</style></defs><path class="cls-1" d="M182,13a4.74,4.74,0,1,0,4.74,4.74A4.74,4.74,0,0,0,182,13Z"/><path class="cls-2" d="M50.88,26.79h6.94L58,29.55A12.54,12.54,0,0,1,72.77,28c3.75,2.3,6,6.45,6,11.56a14.15,14.15,0,0,1-1.6,6.81,12.36,12.36,0,0,1-19,3.42V65.06H50.88Zm7.35,12.76c0,4,2.73,6.89,6.48,6.89s6.48-2.94,6.48-6.89-2.73-6.89-6.48-6.89S58.23,35.59,58.23,39.55Z"/><path class="cls-2" d="M83.26,32.76a12,12,0,0,1,10.8-6.43,11.79,11.79,0,0,1,8.36,3.27l.18-2.73h6.92V52.3h-6.13l-.53-2.73a13.94,13.94,0,0,1-8.8,3.24,11.9,11.9,0,0,1-6.38-1.71c-3.75-2.27-6.05-6.42-6.05-11.53A14.11,14.11,0,0,1,83.26,32.76ZM95.71,46.49c3.76,0,6.49-2.94,6.49-6.92s-2.73-6.86-6.49-6.86-6.48,3-6.48,6.86S92,46.49,95.71,46.49Zm6.46-5.16"/><path class="cls-2" d="M113.77,52.3V26.79h6.43l.35,3.65a8.76,8.76,0,0,1,7.66-4.16,12.78,12.78,0,0,1,3.42.51l-1,6.35a9.23,9.23,0,0,0-2.91-.48c-4,0-6.59,3-6.59,8.39V52.3Z"/><path class="cls-2" d="M134.8,52.3V14h7.35V37.25a43.48,43.48,0,0,1,3.7-4.75l5-5.71h8L149,38.25l11.64,14h-9l-7.3-8.62-2.22,2.6v6Z"/><path class="cls-2" d="M171.9,13V52.3h-7.35V13Z"/><path class="cls-2" d="M192.24,52.3V26.79h6.43L199,29.9a10.6,10.6,0,0,1,8.24-3.62c5.94,0,9.77,4.13,9.77,11.15V52.3h-7.38V38.86c0-4.14-1.55-6.2-4.64-6.2s-5.38,2.45-5.38,6.71V52.3Z"/><path class="cls-2" d="M25.49,49.37,29,43.76a32.43,32.43,0,0,0,4.1,2.34,8.12,8.12,0,0,0,3.35.87,3.06,3.06,0,0,0,1.84-.52A1.57,1.57,0,0,0,39,45.13a2.16,2.16,0,0,0-1.3-1.89,30.71,30.71,0,0,0-3-1.42c-1.21-.5-2.42-1.06-3.64-1.69A9.68,9.68,0,0,1,28,37.68a5.91,5.91,0,0,1-1.23-3.88,6.68,6.68,0,0,1,2.6-5.4c1.73-1.41,4.19-2.12,7.36-2.12a22.14,22.14,0,0,1,4.9.58,15.38,15.38,0,0,1,4.69,1.88L43,34.4a12.29,12.29,0,0,0-3-1.33A10.46,10.46,0,0,0,37,32.49a4.79,4.79,0,0,0-1.59.29,1.15,1.15,0,0,0-.87,1.15,1.82,1.82,0,0,0,1.2,1.58,28.88,28.88,0,0,0,2.86,1.27c1.19.46,2.41,1,3.67,1.64a9.89,9.89,0,0,1,3.2,2.51,6.18,6.18,0,0,1,1.3,4.1,7,7,0,0,1-1.39,4.37,8.89,8.89,0,0,1-3.73,2.8,13.15,13.15,0,0,1-5.14,1A17.86,17.86,0,0,1,31,52.24,18.87,18.87,0,0,1,25.49,49.37Z"/><path class="cls-2" d="M182,27.54a9.76,9.76,0,0,1-3.67-.73V52.3h7.35V26.81A9.78,9.78,0,0,1,182,27.54Z"/></svg></a>
      <p data-i18n="footer.desc"><?= tr('footer.desc') ?></p>
      <div class="footer-socials">
        <a href="https://www.linkedin.com/company/sparklin1/posts/?feedView=all" class="footer-social-btn footer-social-orange" title="LinkedIn" target="_blank" rel="noopener"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
        <a href="https://x.com/sparklinplug1" class="footer-social-btn footer-social-orange" title="X / Twitter" target="_blank" rel="noopener"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
        <a href="https://www.youtube.com/channel/UCPqWSe155JG7i8oSfyW8sGw" class="footer-social-btn footer-social-orange" title="YouTube" target="_blank" rel="noopener"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="currentColor" stroke="none"/></svg></a>
        <a href="https://www.facebook.com/SparklinCharge/" class="footer-social-btn footer-social-orange" title="Facebook" target="_blank" rel="noopener"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>
      </div>
      <div class="sk-lang-wrap" id="sk-lang-wrap-footer" style="position:relative;margin-top:16px;">
      <button onclick="toggleLangMenuFooter(event)"
        style="display:flex;align-items:center;gap:6px;padding:6px 12px;background:none;border:1px solid rgba(255,255,255,.15);border-radius:8px;cursor:pointer;font-family:inherit;font-size:12px;font-weight:600;color:rgba(255,255,255,.55);transition:all .15s;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        <span id="sk-lang-label-footer"><?= strtoupper(lang()) ?></span>
        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" id="sk-lang-caret-footer" style="transition:transform .2s"><polyline points="6 9 12 15 18 9"/></svg>
      </button>
      <div id="sk-lang-menu-footer" role="listbox"
        style="display:none;position:absolute;bottom:calc(100% + 8px);left:0;background:#fff;border:1px solid var(--border);border-radius:10px;box-shadow:0 -8px 32px rgba(26,26,46,.15);min-width:180px;padding:5px;z-index:999;">
      <button class="sk-lang-opt sk-lang-opt-dark" data-lang="fr"<?= lang()==='fr'?' style="background:rgba(255,255,255,.10);font-weight:700;color:#fff;"':'' ?> href="?lang=fr" title="Français"><span class="sk-lang-flag">🇫🇷</span><span>Français</span></button>
      <button class="sk-lang-opt sk-lang-opt-dark" data-lang="en"<?= lang()==='en'?' style="background:rgba(255,255,255,.10);font-weight:700;color:#fff;"':'' ?> href="?lang=en" title="English"><span class="sk-lang-flag">🇬🇧</span><span>English</span></button>
      <button class="sk-lang-opt sk-lang-opt-dark" data-lang="de"<?= lang()==='de'?' style="background:rgba(255,255,255,.10);font-weight:700;color:#fff;"':'' ?> href="?lang=de" title="Deutsch"><span class="sk-lang-flag">🇩🇪</span><span>Deutsch</span></button>
      <button class="sk-lang-opt sk-lang-opt-dark" data-lang="es"<?= lang()==='es'?' style="background:rgba(255,255,255,.10);font-weight:700;color:#fff;"':'' ?> href="?lang=es" title="Español"><span class="sk-lang-flag">🇪🇸</span><span>Español</span></button>
      <button class="sk-lang-opt sk-lang-opt-dark" data-lang="th"<?= lang()==='th'?' style="background:rgba(255,255,255,.10);font-weight:700;color:#fff;"':'' ?> href="?lang=th" title="ภาษาไทย"><span class="sk-lang-flag">🇹🇭</span><span>ภาษาไทย</span></button>
      <button class="sk-lang-opt sk-lang-opt-dark" data-lang="ms"<?= lang()==='ms'?' style="background:rgba(255,255,255,.10);font-weight:700;color:#fff;"':'' ?> href="?lang=ms" title="Bahasa Melayu"><span class="sk-lang-flag">🇲🇾</span><span>Bahasa Melayu</span></button>
      <button class="sk-lang-opt sk-lang-opt-dark" data-lang="id"<?= lang()==='id'?' style="background:rgba(255,255,255,.10);font-weight:700;color:#fff;"':'' ?> href="?lang=id" title="Bahasa Indonesia"><span class="sk-lang-flag">🇮🇩</span><span>Bahasa Indonesia</span></button>
      </div>
    </div>
    </div>

    <!-- COL 1 : Gérer vos bornes -->
    <div class="footer-col">
      <h4 data-i18n="footer.h1"><?= tr('footer.h1') ?></h4>
      <ul>
        <li><a href="/spark-pilot/">Spark Pilot</a></li>
        <li><a href="/app/">Sparklin App</a></li>
        <li><a href="https://manager.sparklin.io" data-i18n="footer.lnk.manager"><?= tr('footer.lnk.manager') ?></a></li>
        <li><a href="https://setup.sparklin.io" data-i18n="footer.lnk.activate"><?= tr('footer.lnk.activate') ?></a></li>
      </ul>
    </div>

    <!-- COL 2 : Solutions de recharge -->
    <div class="footer-col">
      <h4 data-i18n="footer.h2"><?= tr('footer.h2') ?></h4>
      <ul>
        <li><a href="/spark-1/">Spark 1 — 3,7 kW</a></li>
        <li><a href="/spark-plus/">Spark Plus — 3,7 kW Premium</a></li>
        <li><a href="/spark-go-e/">Spark x go-e — 7–22 kW</a></li>
      </ul>
    </div>

    <!-- COL 3 : Cas clients -->
    <div class="footer-col">
      <h4 data-i18n="footer.h3"><?= tr('footer.h3') ?></h4>
      <ul>
        <li><a href="/cas/pme/" data-i18n="footer.lnk.pme"><?= tr('footer.lnk.pme') ?></a></li>
        <li><a href="/cas/collaborateurs/" data-i18n="footer.lnk.collab"><?= tr('footer.lnk.collab') ?></a></li>
        <li><a href="/cas/hotel/">Hôtel &amp; Commerce</a></li>
        <li><a href="/cas/camping/">Camping &amp; Hébergement</a></li>
        <li><a href="/cas/collectivite/">Collectivité / Parking public</a></li>
      </ul>
    </div>

    <!-- COL 4 : Ressources -->
    <div class="footer-col">
      <h4 data-i18n="footer.h4"><?= tr('footer.h4') ?></h4>
      <ul>
        <li><a href="/livre-blanc/">Livre blanc 2026 <span class="footer-badge" data-i18n="footer.new"><?= tr('footer.new') ?></span></a></li>
        <li><a href="/blog/">Blog IRVE</a></li>
        <li><a href="/">Notre offre</a></li>
        <li><a href="/a-propos/">Notre société</a></li>
        <li><a href="/contact/">Nous contacter</a></li>
        <li><a href="/support/">Support &amp; FAQ</a></li>
        <li><a href="/evenements/">Événements</a></li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <span class="footer-bottom-left" data-i18n="footer.legal"><?= tr('footer.legal') ?></span>
    <nav class="footer-bottom-links">
      <a href="https://www.sparklin.io/mentions-légales" data-i18n="footer.lnk.legal"><?= tr('footer.lnk.legal') ?></a>
      <a href="https://www.sparklin.io/cgu-app" data-i18n="footer.lnk.cgu"><?= tr('footer.lnk.cgu') ?></a>
      <a href="/contact/" data-i18n="footer.lnk.contact"><?= tr('footer.lnk.contact') ?></a>
      <a href="https://manager.sparklin.io">Spark Pilot</a>
    </nav>
  </div>
</footer>
<script src="/assets/js/app.js"></script>
<script src="/assets/js/sparklin-interconnect.js"></script>

<script>
(function() {
  function toggleMega(id) {
    var panels = document.querySelectorAll('.nav-mega-panel');
    var items  = document.querySelectorAll('.nav-mega-item');
    var el     = document.getElementById(id);
    if (!el) return;
    var panel = el.querySelector('.nav-mega-panel');
    var isOpen = panel && panel.style.display === 'flex';
    // close all
    panels.forEach(function(p) { p.style.display = 'none'; });
    items.forEach(function(i)  { i.classList.remove('open'); });
    // open if was closed
    if (!isOpen && panel) {
      panel.style.display = 'flex';
      el.classList.add('open');
    }
  }
  document.addEventListener('click', function(e) {
    if (!e.target.closest || !e.target.closest('.nav-mega-item')) {
      document.querySelectorAll('.nav-mega-panel').forEach(function(p) {
        p.style.display = 'none';
      });
      document.querySelectorAll('.nav-mega-item').forEach(function(i) {
        i.classList.remove('open');
      });
    }
  });
  window.toggleMega = toggleMega;
})();
</script>





<!-- ══ CRISP LIVECHAT ══════════════════════════════════════════
     Module: modules/addon/crisp
     Website ID: 326a0f31-24a5-4709-9538-ff5f4aa65f71
     ══════════════════════════════════════════════════════════ -->
<script type="text/javascript">
  window.$crisp=[];
  window.CRISP_WEBSITE_ID="326a0f31-24a5-4709-9538-ff5f4aa65f71";
  (function(){
    var d=document;
    var s=d.createElement("script");
    s.src="https://client.crisp.chat/l.js";
    s.async=1;
    d.getElementsByTagName("head")[0].appendChild(s);
  })();
</script>
<!-- ══ /CRISP ════════════════════════════════════════════════ -->


<script>
function toggleMobileNav() {
  var btn = document.getElementById('nav-hamburger');
  var drawer = document.getElementById('nav-mobile-drawer');
  var isOpen = drawer.classList.contains('open');
  btn.classList.toggle('open', !isOpen);
  drawer.classList.toggle('open', !isOpen);
  document.body.classList.toggle('nav-open', !isOpen);
}
function toggleDrawerSection(trigger) {
  var links = trigger.nextElementSibling;
  var isOpen = links.classList.contains('open');
  trigger.classList.toggle('open', !isOpen);
  links.classList.toggle('open', !isOpen);
}
// Close drawer on nav link click
document.addEventListener('DOMContentLoaded', function() {
  var drawer = document.getElementById('nav-mobile-drawer');
  if (drawer) {
    drawer.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', function() {
        drawer.classList.remove('open');
        document.getElementById('nav-hamburger').classList.remove('open');
        document.body.classList.remove('nav-open');
      });
    });
  }
});
</script>

<!-- ══ COOKIE BANNER ══ -->
<div id="sk-cookie-banner" aria-live="polite" role="region" aria-label="Politique de cookies" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9999;background:#FFFFFF;border-top:1px solid #E8E6E0;box-shadow:0 -4px 24px rgba(26,26,46,0.08);padding:14px clamp(16px,4vw,60px);font-family:'Wix Madefor Text','DM Sans',system-ui,sans-serif;animation:skCookieIn .3s ease-out;">
  <div style="max-width:1280px;margin:0 auto;display:flex;align-items:center;gap:20px;flex-wrap:wrap;justify-content:space-between;">
    <p style="margin:0;font-size:13px;color:#4A4A6A;line-height:1.5;flex:1;min-width:240px;">
      Nous utilisons des cookies essentiels au fonctionnement du site et, avec votre accord, des cookies analytiques pour améliorer votre expérience.
      <a href="/politique-cookies/" style="color:#E8563A;text-decoration:none;white-space:nowrap;font-weight:500;">En savoir plus</a>
    </p>
    <div style="display:flex;gap:10px;align-items:center;flex-shrink:0;">
      <button id="sk-cookie-reject" onclick="skCookieChoice(false)" style="padding:8px 18px;font-size:13px;font-weight:500;color:#4A4A6A;background:transparent;border:1px solid #D4D0C8;border-radius:8px;cursor:pointer;font-family:inherit;white-space:nowrap;transition:border-color .2s,color .2s;">Refuser</button>
      <button id="sk-cookie-accept" onclick="skCookieChoice(true)" style="padding:8px 20px;font-size:13px;font-weight:600;color:#fff;background:#E8563A;border:1px solid transparent;border-radius:8px;cursor:pointer;font-family:inherit;white-space:nowrap;transition:background .2s;">Accepter</button>
    </div>
  </div>
</div>
<style>
@keyframes skCookieIn { from{transform:translateY(100%);opacity:0} to{transform:translateY(0);opacity:1} }
#sk-cookie-reject:hover { border-color:#4A4A6A; color:#1A1A2E; }
#sk-cookie-accept:hover { background:#FF6B4A; }

/* ── SPECIFIC FIXES (injected corrections) ───────────────────── */

/* HOME — Spark Pilot net-feats: 1 col on mobile */
@media (max-width: 640px) {
  .home-net-feats { grid-template-columns: 1fr !important; }
  .home-net-feat {
    padding: 20px !important;
    display: flex !important;
    gap: 14px !important;
    align-items: flex-start !important;
  }
  .home-net-feat > div:first-child { flex-shrink: 0; }
}

/* APP — Fonctionnalités: 1 col on mobile */
@media (max-width: 640px) {
  .app-feats-grid { grid-template-columns: 1fr !important; }
}
@media (max-width: 900px) {
  .app-feats-grid { grid-template-columns: 1fr 1fr !important; }
}

/* GO-E — Deux versions: 1 col on mobile */
@media (max-width: 640px) {
  .goe-versions-grid {
    grid-template-columns: 1fr !important;
    gap: 20px !important;
    max-width: 100% !important;
  }
}

/* CONTACT — Mobile layout */
@media (max-width: 900px) {
  .contact-layout {
    grid-template-columns: 1fr !important;
    gap: 40px !important;
  }
  /* Sticky panel becomes normal flow */
  .contact-layout > div:last-child > div[style*="sticky"] {
    position: static !important;
  }
}
@media (max-width: 640px) {
  .contact-layout { gap: 32px !important; }
  .contact-form-row {
    grid-template-columns: 1fr !important;
    gap: 16px !important;
  }
  /* Form fields full width */
  .contact-layout input,
  .contact-layout select,
  .contact-layout textarea,
  .contact-layout button[type="submit"] {
    width: 100% !important;
    box-sizing: border-box !important;
    font-size: 16px !important; /* prevents iOS zoom on focus */
  }
  .contact-layout button[type="submit"] {
    padding: 16px !important;
    font-size: 16px !important;
  }
}

</style>
<script>
(function(){
  var COOKIE_KEY = 'sk_cookie_consent';
  function skCookieChoice(accepted) {
    try {
      localStorage.setItem(COOKIE_KEY, accepted ? 'accepted' : 'refused');
      localStorage.setItem(COOKIE_KEY + '_date', new Date().toISOString());
    } catch(e){}
    var banner = document.getElementById('sk-cookie-banner');
    if (banner) {
      banner.style.animation = 'skCookieIn .25s ease-out reverse';
      setTimeout(function(){ banner.style.display = 'none'; }, 240);
    }
    if (accepted) {
      // Ici : activer analytique (GA, etc.) si besoin
    }
  }
  window.skCookieChoice = skCookieChoice;
  function skCookieInit() {
    try {
      var consent = localStorage.getItem(COOKIE_KEY);
      if (consent) return; // déjà répondu
    } catch(e){}
    var banner = document.getElementById('sk-cookie-banner');
    if (banner) {
      banner.style.display = 'block';
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(){ setTimeout(skCookieInit, 800); });
  } else {
    setTimeout(skCookieInit, 800);
  }
})();
</script>
</body>
</html>