<?php
require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/i18n.php';
loadEnv(__DIR__ . '/../.env');
$lang = initI18n();
?>
<!DOCTYPE html>
<html lang="<?= lang() ?>">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>CGU des applications — Spark Pilot, Spark-A, Spark-i — Sparklin</title>
  <meta name="description" content="Conditions générales d'utilisation des applications mobiles Sparklin (Spark-A, Spark-i) et de la plateforme Spark Pilot."/>
  <link rel="icon" href="/favicon.ico" sizes="any"/>
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16.png"/>
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;500;600;700;800&family=Wix+Madefor+Text:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/assets/css/style.css">
  <?php require_once $_SERVER['DOCUMENT_ROOT'].'/includes/seo.php'; sk_seo_head(['title'=>'CGU des applications — Spark Pilot, Spark-A, Spark-i — Sparklin', 'desc'=>"Conditions générales d'utilisation des applications mobiles Sparklin (Spark-A, Spark-i) et de la plateforme Spark Pilot."]); ?>
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

<style>
.legal-doc { max-width: 760px; }
.legal-doc h1.section-title { margin-bottom: 6px; }
.legal-doc .legal-updated { font-size: 12.5px; color: var(--text-light); margin-bottom: 36px; display:block; }
.legal-doc h2 { font-family: var(--font-display); font-size: 1.25rem; font-weight: 700; color: var(--dark); letter-spacing: -0.01em; margin: 40px 0 14px; }
.legal-doc h2:first-of-type { margin-top: 8px; }
.legal-doc h3 { font-family: var(--font-body); font-size: 0.95rem; font-weight: 700; color: var(--dark); margin: 22px 0 10px; }
.legal-doc p { font-size: 15.5px; color: var(--text-mid); line-height: 1.75; font-weight: 300; margin-bottom: 16px; }
.legal-doc ul, .legal-doc ol { margin: 0 0 16px; padding-left: 22px; }
.legal-doc li { font-size: 15.5px; color: var(--text-mid); line-height: 1.75; font-weight: 300; margin-bottom: 8px; }
.legal-doc a { color: var(--orange); font-weight: 500; text-decoration: none; }
.legal-doc a:hover { text-decoration: underline; }
.legal-doc strong { color: var(--dark); font-weight: 600; }
.legal-toc { background: var(--bg-off); border: 1px solid var(--border); border-radius: 16px; padding: 22px 26px; margin: 28px 0 40px; }
.legal-toc div { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: var(--text-light); margin-bottom: 12px; }
.legal-toc ol { margin: 0; padding-left: 18px; columns: 2; column-gap: 28px; }
.legal-toc li { font-size: 13.5px; margin-bottom: 7px; }
.legal-toc a { color: var(--text-mid); font-weight: 400; }
.legal-toc a:hover { color: var(--orange); }
.legal-card { background: var(--bg-off); border: 1px solid var(--border); border-radius: 16px; padding: 26px 28px; margin-top: 40px; }
.legal-card p { margin-bottom: 6px; font-size: 14.5px; }
.legal-card p:last-child { margin-bottom: 0; }
.legal-note { background: rgba(232,86,58,0.06); border: 1px solid rgba(232,86,58,0.22); border-radius: 12px; padding: 16px 20px; margin-bottom: 32px; }
.legal-note p { margin: 0; font-size: 13.5px; color: var(--text-mid); }
@media (max-width: 640px) { .legal-toc ol { columns: 1; } }
</style>

<main style="padding-top:64px;">
<section style="background:var(--bg-white);padding:72px 0 80px;">
  <div class="sk-wrap legal-doc">
    <span class="section-label">Conditions générales</span>
    <h1 class="section-title">CGU des applications Sparklin</h1>
    <span class="legal-updated">Applications Spark-A, Spark-i et Spark Pilot — en vigueur au 01/04/2023</span>

    <div class="legal-toc">
      <div>Sommaire</div>
      <ol>
        <li><a href="#infos-legales">Informations légales</a></li>
        <li><a href="#definitions">Définitions</a></li>
        <li><a href="#contact">Contact</a></li>
        <li><a href="#objet">Objet</a></li>
        <li><a href="#acceptation">Acceptation des CGU</a></li>
        <li><a href="#engagements">Engagements de Sparklin</a></li>
        <li><a href="#obligations">Obligations des utilisateurs</a></li>
        <li><a href="#responsabilites">Responsabilités</a></li>
        <li><a href="#conditions-financieres">Conditions financières</a></li>
        <li><a href="#paiement">Service de paiement intégré</a></li>
        <li><a href="#facturation">Facturation</a></li>
        <li><a href="#liens">Liens hypertextes</a></li>
        <li><a href="#donnees">Données personnelles</a></li>
        <li><a href="#pi">Propriété intellectuelle</a></li>
        <li><a href="#droit">Droit applicable</a></li>
        <li><a href="#duree">Durée</a></li>
        <li><a href="#mediation">Notification, réclamation, médiation</a></li>
        <li><a href="#annexe-a">Annexe A — Services Sparklin</a></li>
        <li><a href="#annexe-b">Annexe B — Applications mobiles</a></li>
      </ol>
    </div>

    <h2 id="infos-legales">Informations légales</h2>
    <p>En vertu de l'article 6 de la Loi n°2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique, il est précisé dans cet article l'identité des différents intervenants dans le cadre de la réalisation et du suivi du présent contrat.</p>
    <p>Les applications mobiles Spark-A et Spark-i sont développées et éditées par la société Sparklin SAS, RCS Nantes B 894 896 448, domiciliée à l'adresse 4 rue de la Cornouaille, 44300 Nantes, France.</p>
    <p>Téléphone&nbsp;: 02 85 52 26 35 — courriel&nbsp;: <a href="mailto:contact@sparklin.io">contact@sparklin.io</a></p>
    <p>Hébergeur&nbsp;:<br>Trident Media Guard SA,<br>RCS 441 392 586<br>4 rue de la Cornouaille, 44300 Nantes, France</p>

    <h2 id="definitions">Définitions</h2>
    <p>Pour le présent Contrat les termes suivants sont définis comme suit&nbsp;:</p>
    <p><strong>«&nbsp;Application Utilisateur&nbsp;»</strong>&nbsp;: désigne l'application mobile SPARKLIN installée sur le téléphone de l'Utilisateur pour les besoins de l'utilisation de la Prise et qui comprennent les Services SPARKLIN ainsi que tous les contenus, outils, fonctions et fonctionnalités proposés, à savoir&nbsp;:</p>
    <ol>
      <li><strong>Application Spark-A</strong>&nbsp;: l'application destinée aux Utilisateurs des Prises Sparklin et leur permettant d'accéder aux Services Sparklin, publiée sur le marché d'application Google Play®.</li>
      <li><strong>Application Spark-i</strong>&nbsp;: l'application destinée aux Utilisateurs des Prises Sparklin et leur permettant d'accéder aux Services Sparklin, publiée sur le marché d'application App Store®.</li>
      <li><strong>«&nbsp;Application Installateur&nbsp;»</strong>&nbsp;: désigne l'application mobile SPARKLIN installée sur le téléphone de l'Installateur pour les besoins de l'installation de la Prise à la demande du Gérant. L'Application Installateur permet notamment à l'Installateur de faire la mise en service logicielle après installation et raccordement de la Prise.</li>
    </ol>
    <p><strong>«&nbsp;Application Spark Pilot&nbsp;»</strong>&nbsp;: désigne l'application web SPARKLIN installée sur le téléphone du Gérant pour le suivi de fonctionnement des Prises et la gestion des Utilisateurs et de la Communauté.</p>
    <p><strong>«&nbsp;Communauté&nbsp;»</strong>&nbsp;: un ensemble d'Utilisateurs identifiés et ayant des droits d'usage et des conditions d'usage spécifiques et particulières à un ensemble de Prises défini par le Gérant.</p>
    <p><strong>«&nbsp;Compte Sparklin&nbsp;»</strong>&nbsp;: désigne l'espace personnel créé par l'Utilisateur lors de son inscription sur l'Application Utilisateur.</p>
    <p><strong>«&nbsp;Contrat&nbsp;»</strong> ou <strong>«&nbsp;CGU&nbsp;»</strong> ou <strong>«&nbsp;Conditions Générales d'Utilisation&nbsp;»</strong>&nbsp;: le présent document contractuel qui encadre les conditions d'accès et d'utilisation par l'Utilisateur à l'Application Utilisateur et aux Services Sparklin.</p>
    <p><strong>«&nbsp;Donnée(s)&nbsp;»</strong>&nbsp;: désigne l'ensemble des informations et données, y compris toutes Données Personnelles, saisies, entrées ou téléchargées, automatiquement ou par l'Utilisateur, dans l'Application Utilisateur.</p>
    <p><strong>«&nbsp;Donnée(s) Personnelle(s)&nbsp;»</strong>&nbsp;: désigne toute information se rapportant à une personne physique identifiée ou identifiable&nbsp;; est réputée être une «&nbsp;personne physique identifiable&nbsp;», une personne physique qui peut être identifiée, directement ou indirectement, notamment par référence à un identifiant, tel qu'un nom, un numéro d'identification, des données de localisation, un identifiant en ligne, ou à un ou plusieurs éléments spécifiques propres à son identité physique, physiologique, génétique, psychique, économique, culturelle ou sociale, y compris des données codifiées ou pseudonymes dès lors que l'utilisation desdites données permet de réattribuer celles-ci à une personne identifiée ou identifiable telle que définie ci-dessus.</p>
    <p><strong>«&nbsp;Installateur&nbsp;»</strong> désigne les sous-traitants (électriciens) de SPARKLIN pour les besoins de l'installation de la Prise.</p>
    <p><strong>«&nbsp;Gérant&nbsp;»</strong>&nbsp;: désigne le fournisseur d'hébergement ou le gestionnaire de lieux privés ou publics équipés de Prises. Il s'agit de l'organisation propriétaire de la Prise, ou la personne désignée en tant que gérant par le propriétaire de la Prise, en charge d'assurer la gestion des Utilisateurs et de la Communauté. Le Gérant contracte l'offre de service auprès de Sparklin et fixe notamment les règles d'utilisation des Prises.</p>
    <p><strong>«&nbsp;Partenaires Sparklin&nbsp;»</strong> ou <strong>«&nbsp;Partenaires&nbsp;»</strong>&nbsp;: toute personne morale ayant une relation contractuelle avec la société Sparklin autre que les clients, notamment le prestataire de Service de Paiement Intégré.</p>
    <p><strong>«&nbsp;Point de Recharge Sparklin&nbsp;»</strong>&nbsp;: un lieu physique équipé d'une ou de plusieurs Prises.</p>
    <p><strong>«&nbsp;Prise(s)&nbsp;»</strong>&nbsp;: désigne la prise électrique, conçue et fabriquée par SPARKLIN, destinée à la recharge de véhicules électriques ou hybrides rechargeables, connectée à un réseau de communication permettant la mise en œuvre des Services Sparklin au bénéfice d'Utilisateurs.</p>
    <p><strong>«&nbsp;Service de Paiement Intégré&nbsp;»</strong>&nbsp;: l'un des services proposés par Sparklin, par l'intermédiaire de son prestataire, qui permet à un Utilisateur de commander et de suivre l'exécution d'un Service puis de valider le paiement d'un Service Sparklin depuis une des Applications Mobiles Sparklin.</p>
    <p><strong>«&nbsp;Services Sparklin&nbsp;»</strong>&nbsp;: l'ensemble des services fournis par Sparklin qui peuvent être fournis aux Utilisateurs, tels que listés et définis en Annexe A.</p>
    <p><strong>«&nbsp;Transaction&nbsp;»</strong>&nbsp;: le processus commençant par la commande d'un Service Sparklin par un Utilisateur, l'exécution de cette commande et des Services afférents, et enfin la validation du paiement par l'Utilisateur.</p>
    <p><strong>«&nbsp;Utilisateur(s)&nbsp;»</strong>&nbsp;: tout utilisateur, particulier ou membre d'une organisation, qui a acquis des droits d'utilisation des Prises et qui, à ce titre, bénéficie d'un Compte Sparklin ou d'un droit de création de Compte Sparklin lui permettant l'accès aux Prises.</p>

    <h2 id="contact">Contact</h2>
    <p>Pour toute question ou demande d'information concernant l'application, ou tout signalement de contenu ou d'activités illicites, l'Utilisateur peut contacter l'éditeur à l'adresse de messagerie électronique suivante&nbsp;: <a href="mailto:contact@sparklin.io">contact@sparklin.io</a>, ou adresser un courrier à&nbsp;:</p>
    <div class="legal-card">
      <p><strong>SAS Sparklin</strong></p>
      <p>4 rue de la Cornouaille</p>
      <p>44300 Nantes, France</p>
    </div>

    <h2 id="objet">Objet</h2>
    <p>Les présentes Conditions Générales d'Utilisation ont pour objet de définir&nbsp;:</p>
    <ol>
      <li>Les modalités et conditions selon lesquelles Sparklin met à disposition des Utilisateurs l'Application Utilisateur et les Services Sparklin&nbsp;;</li>
      <li>Les droits et les obligations des Utilisateurs dans le cadre de l'utilisation de l'Application Utilisateur et des Services Sparklin.</li>
    </ol>

    <h2 id="acceptation">Acceptation des CGU</h2>
    <p>Les CGU sont mises à disposition des Utilisateurs au sein de l'Application Utilisateur, dans l'onglet dédié où elles sont directement consultables.</p>
    <p>L'accès et l'utilisation de l'Application Utilisateur sont soumis à l'acceptation et au respect des présentes Conditions Générales d'Utilisation. L'acceptation explicite de chaque Utilisateur sera demandée lors de sa première connexion aux Services Sparklin à travers l'Application Utilisateur. Au moment de la création d'un Compte Sparklin, prévue dans les conditions de l'article «&nbsp;Conditions d'accès&nbsp;», l'Utilisateur doit consulter et accepter les CGU.</p>
    <p>Sparklin se réserve le droit de modifier, à tout moment et sans préavis, les présentes Conditions Générales d'Utilisation. Dans un tel cas, Sparklin portera à la connaissance des Utilisateurs les CGU modifiées ainsi que leur date d'entrée en vigueur. L'Utilisateur peut refuser ces modifications dans un délai d'un (1) mois suivant la notification de la modification, par courrier recommandé avec accusé de réception ou à l'adresse électronique suivante&nbsp;: <a href="mailto:contact@sparklin.io">contact@sparklin.io</a>. Un tel refus entraînera la perte de la possibilité d'utiliser les Services Sparklin sur l'Application Utilisateur. La poursuite de l'utilisation de l'Application Utilisateur et des Services Sparklin vaudra acceptation des CGU modifiées. La nouvelle version des CGU sera disponible dans un onglet de l'Application Utilisateur avec sa date d'entrée en vigueur.</p>
    <p>De même, Sparklin apportera de temps à autre des modifications à l'Application Utilisateur ainsi qu'aux Services Sparklin, sans que ces changements n'entraînent de droits supplémentaires ni de dédommagements ou autres recours au bénéfice des Utilisateurs, ceux-ci restant libres à tout moment de renoncer aux Services Sparklin.</p>

    <h2 id="engagements">Engagements de Sparklin</h2>
    <h3>1. Pré-requis technique</h3>
    <p>L'Application Utilisateur et les Services Sparklin sont accessibles à tout Utilisateur ayant un accès internet. Tous les frais nécessaires pour l'accès aux services informatiques (matériel informatique, connexion Internet…) sont à la charge de l'Utilisateur.</p>
    <p>L'Utilisateur s'engage, en souscrivant aux présentes CGU, à disposer d'une connexion sécurisée garantissant une utilisation de l'Application Utilisateur et des Services Sparklin conforme aux conditions prévues par les présentes.</p>
    <h3>2. Inscription</h3>
    <p>L'accès à l'Application Utilisateur et aux Services Sparklin est ouvert aux Utilisateurs autorisés.</p>
    <p>L'Utilisateur est informé par courriel qu'un accès à l'Application Utilisateur lui a été créé par le Gérant. L'Utilisateur doit alors télécharger et se connecter à l'Application Utilisateur. Il doit renseigner son numéro de téléphone aux fins de vérification de son autorisation. L'Utilisateur pourra créer son Compte Sparklin si son accès a été autorisé par un Gérant.</p>
    <p>L'accès à l'Application Utilisateur est définitivement validé lorsque l'Utilisateur accepte les présentes CGU ainsi que la Politique de confidentialité en cochant les cases prévues à cet effet, lors de la première connexion.</p>
    <p>L'utilisation de l'Application Utilisateur par des personnes physiques est réservée aux personnes majeures ou bénéficiant d'un accord parental ou d'un tuteur légal. Sparklin se réserve le droit de demander une justification de l'âge de l'Utilisateur, ou d'un accord parental ou du tuteur légal, à tout moment et par tout moyen.</p>
    <p>Pour créer un Compte Sparklin, l'Utilisateur doit renseigner au Gérant les informations suivantes&nbsp;:</p>
    <ul>
      <li>N° de téléphone&nbsp;;</li>
      <li>Nom et prénom (l'Utilisateur ne doit pas renseigner un pseudonyme ou un nom inventé)&nbsp;;</li>
      <li>Adresse email.</li>
    </ul>
    <p>Lors de son inscription, l'Utilisateur s'engage à fournir l'ensemble des informations requises de façon exacte, sincère et à jour, et à les maintenir à jour aussi longtemps qu'il continuera à utiliser les Services Sparklin. L'Utilisateur doit en particulier fournir un numéro de téléphone valide, sur lequel Sparklin lui adressera une confirmation de son inscription. Une même adresse de messagerie électronique ne peut être utilisée pour inscrire plusieurs Utilisateurs aux Services Sparklin.</p>
    <p>Toute communication transmise par Sparklin et ses Partenaires vers les Utilisateurs est réputée avoir été réceptionnée et lue par l'Utilisateur. Ce dernier s'engage donc à consulter régulièrement les messages reçus sur cette adresse de courriel et à répondre dans un délai raisonnable, ou dans les délais indiqués par Sparklin ou ses Partenaires, lorsque cela est demandé.</p>
    <p>Une seule inscription aux Services Sparklin est acceptée par personne physique. Chaque Utilisateur se voit attribuer un identifiant unique lui permettant d'accéder à un espace dont l'accès lui est réservé, son Compte Sparklin, en complément de la saisie de son mot de passe.</p>
    <p>Le mot de passe est unique et confidentiel&nbsp;: l'Utilisateur est responsable de sa confidentialité et reste responsable de l'usage qui peut en être fait en cas de divulgation à des tiers.</p>
    <p>En cas de suspicion d'usage frauduleux ou de perte ou de vol d'identifiant et/ou de mot de passe, l'Utilisateur doit en informer sans délai Sparklin par email à l'adresse suivante&nbsp;: <a href="mailto:sav@sparklin.io">sav@sparklin.io</a>. Sparklin ne pourra être tenue pour responsable de toute utilisation frauduleuse du Compte Sparklin de l'Utilisateur.</p>
    <p>Sparklin se réserve, à sa discrétion, la possibilité de refuser une demande d'inscription à l'Application Utilisateur et aux Services Sparklin.</p>
    <h3>3. Désinscription — Suspension</h3>
    <p>L'Utilisateur inscrit pourra à tout moment demander sa désinscription en se rendant sur la page dédiée dans son Compte Sparklin. Toute désinscription sera effective immédiatement après que l'Utilisateur aura rempli le formulaire prévu à cet effet et confirmé sa demande.</p>
    <p>Une désinscription, quel qu'en soit le motif, ne dédouane pas l'Utilisateur du paiement des sommes possiblement dues à Sparklin ou à ses Partenaires, qui conservent tous droits et moyens de recouvrement jusqu'à extinction de ces créances.</p>
    <p>Sparklin se réserve en outre le droit de suspendre voire de fermer, de plein droit, l'accès aux Services Sparklin en cas de non-respect des présentes CGU et des conditions d'utilisation des Partenaires par les Utilisateurs.</p>
    <p>Par ailleurs, à tout moment, l'Utilisateur peut se voir retirer son autorisation par le Gérant d'accéder et d'utiliser l'Application Utilisateur.</p>
    <p>En cas d'informations erronées ou mensongères communiquées par l'Utilisateur, Sparklin se réserve le droit de suspendre voire de fermer le Compte Sparklin de l'Utilisateur jusqu'à, le cas échéant, rectification des informations.</p>

    <h2 id="obligations">Obligations des utilisateurs</h2>
    <p>Les Utilisateurs s'engagent à respecter les termes et les conditions des présentes CGU et notamment à utiliser l'Application Utilisateur aux seules fins de bénéficier des Services Sparklin.</p>
    <p>L'Utilisateur s'engage en particulier à utiliser l'Application Utilisateur et les Services Sparklin&nbsp;:</p>
    <ul>
      <li>conformément à leur destination&nbsp;;</li>
      <li>dans le respect des Droits de Propriété Intellectuelle, notamment ceux rappelés à la clause «&nbsp;Propriété intellectuelle&nbsp;» des présentes CGU.</li>
    </ul>
    <p>L'Utilisateur s'engage notamment à&nbsp;:</p>
    <ul>
      <li>prendre toutes les précautions nécessaires afin d'éviter la propagation de virus, chevaux de Troie, vers, bombes, ou tout autre outil destiné à endommager, nuire ou entraver l'Application Utilisateur et les Services Sparklin&nbsp;;</li>
      <li>ne pas transmettre de virus ou tout autre programme nuisible ou destructeur, et plus généralement ne pas perturber le fonctionnement de l'Application Utilisateur&nbsp;;</li>
      <li>prendre toutes les mesures appropriées pour protéger ses propres données, logiciels et matériels de la contamination par des virus ou autres formes d'attaques circulant éventuellement via l'Application Utilisateur&nbsp;;</li>
      <li>renseigner des informations et coordonnées exactes, sincères et véritables&nbsp;;</li>
      <li>ne pas mettre en ligne et/ou télécharger sur l'Application Utilisateur des contenus à caractère diffamatoire, injurieux, obscène, pornographique, vulgaire, offensant, agressif, déplacé, violent, menaçant, harcelant, raciste, xénophobe, à connotation sexuelle, incitant à la haine, à la violence ou à la discrimination, encourageant les activités ou l'usage de substances illégales ou, plus généralement, contraires aux finalités de l'Application Utilisateur et des Services Sparklin, de nature à porter atteinte aux droits de Sparklin ou d'un tiers, ou contraires aux bonnes mœurs&nbsp;;</li>
      <li>ne pas porter atteinte aux droits et à l'image de Sparklin, notamment à ses droits de propriété intellectuelle&nbsp;;</li>
      <li>se conformer aux présentes CGU et à la Politique de Confidentialité&nbsp;;</li>
      <li>ne pas utiliser sur l'Application Utilisateur des logiciels ou programmes effectuant des tâches automatisées, quelles qu'en soient les fonctionnalités, une telle pratique étant assimilée à une atteinte à un système automatisé de données&nbsp;;</li>
      <li>ne pas reprendre tout ou partie des contenus disponibles sur l'Application Utilisateur sans l'autorisation des titulaires des droits&nbsp;;</li>
      <li>ne pas reproduire et/ou utiliser la marque, la dénomination sociale, le logo ou tout signe distinctif de Sparklin et/ou d'un tiers sans autorisation.</li>
    </ul>
    <p>En outre, les Utilisateurs s'engagent à signaler à Sparklin, sans délai, tout incident concernant l'Application Utilisateur et les Services Sparklin pouvant avoir des incidences sur leur fonctionnement, et notamment tout acte de piratage, d'hameçonnage ou d'utilisation illicite. Ils s'engagent également à ne pas entraver ou perturber les Services fournis par Sparklin.</p>
    <p>Enfin, les Utilisateurs s'engagent à utiliser l'Application Utilisateur et les Services Sparklin conformément aux dispositions légales et réglementaires applicables.</p>
    <p>À défaut du respect des présentes stipulations, le Compte Sparklin pourra être suspendu ou fermé à l'appréciation de Sparklin, conformément aux présentes CGU.</p>

    <h2 id="responsabilites">Responsabilités</h2>
    <p>Les Utilisateurs acceptent expressément que l'utilisation de l'Application Utilisateur et des Services Sparklin se fasse sous leur responsabilité.</p>
    <p>Sparklin n'est responsable que du contenu qu'elle a elle-même édité, sans pour autant garantir l'exactitude, la complétude et l'actualité des informations diffusées.</p>
    <p>Sparklin n'est pas responsable&nbsp;:</p>
    <ul>
      <li>en cas de difficultés liées au service de traitement des paiements fourni aux Utilisateurs par le prestataire de Service de Paiement Intégré&nbsp;;</li>
      <li>en cas de problème ou de défaillances techniques, informatiques, réseaux ou autres, ou en cas d'incompatibilité de l'Application Utilisateur avec un matériel ou logiciel tiers nécessaire à sa bonne exécution ou utilisation&nbsp;;</li>
      <li>des dommages directs ou indirects, matériels ou immatériels, prévisibles ou imprévisibles, résultant de l'utilisation ou des difficultés d'utilisation de l'Application Utilisateur ou des Services Sparklin, à l'exception des cas prévus par la loi&nbsp;;</li>
      <li>des conséquences pour l'Utilisateur des caractéristiques intrinsèques d'Internet, notamment celles relatives au manque de fiabilité et au défaut de sécurisation des informations y circulant&nbsp;;</li>
      <li>des contenus ou activités illicites réalisés par l'Utilisateur sur l'Application Utilisateur.</li>
    </ul>
    <p>L'Utilisateur est responsable&nbsp;:</p>
    <ul>
      <li>de la protection de son matériel et de ses Données&nbsp;;</li>
      <li>de l'utilisation qu'il fait de l'Application Utilisateur et des Services Sparklin&nbsp;;</li>
      <li>du non-respect de la lettre ou de l'esprit des présentes CGU.</li>
    </ul>

    <h2 id="conditions-financieres">Conditions financières</h2>
    <div class="legal-note"><p><strong>Note&nbsp;:</strong> le document source transmis par Sparklin reprend, sous cette rubrique, le même texte que la section «&nbsp;Responsabilités&nbsp;» ci-dessus plutôt que les conditions tarifaires proprement dites. Ce paragraphe est reproduit à l'identique en attendant la version corrigée.</p></div>
    <p>Les Utilisateurs acceptent expressément que l'utilisation de l'Application Utilisateur et des Services Sparklin se fasse sous leur responsabilité.</p>
    <p>Sparklin n'est responsable que du contenu qu'elle a elle-même édité, sans pour autant garantir l'exactitude, la complétude et l'actualité des informations diffusées.</p>
    <p>Sparklin n'est pas responsable&nbsp;:</p>
    <ul>
      <li>en cas de difficultés liées au service de traitement des paiements fourni aux Utilisateurs par le prestataire de Service de Paiement Intégré&nbsp;;</li>
      <li>en cas de problème ou de défaillances techniques, informatiques, réseaux ou autres, ou en cas d'incompatibilité de l'Application Utilisateur avec un matériel ou logiciel tiers nécessaire à sa bonne exécution ou utilisation&nbsp;;</li>
      <li>des dommages directs ou indirects, matériels ou immatériels, prévisibles ou imprévisibles, résultant de l'utilisation ou des difficultés d'utilisation de l'Application Utilisateur ou des Services Sparklin, à l'exception des cas prévus par la loi&nbsp;;</li>
      <li>des conséquences pour l'Utilisateur des caractéristiques intrinsèques d'Internet, notamment celles relatives au manque de fiabilité et au défaut de sécurisation des informations y circulant&nbsp;;</li>
      <li>des contenus ou activités illicites réalisés par l'Utilisateur sur l'Application Utilisateur.</li>
    </ul>
    <p>L'Utilisateur est responsable de la protection de son matériel et de ses Données, ainsi que de l'utilisation qu'il fait de l'Application Utilisateur et des Services Sparklin.</p>

    <h2 id="paiement">Service de paiement intégré</h2>
    <p>Sparklin a conclu un accord avec un Partenaire pour le traitement des paiements effectués à l'aide du Service de Paiement Intégré et la conservation des informations relatives aux cartes bancaires des Utilisateurs. Pour pouvoir utiliser le Service de Paiement Intégré, l'Utilisateur conclura un contrat directement avec le Partenaire prestataire de services de paiement et acceptera ses conditions générales. Il sera demandé aux Utilisateurs de le confirmer lors de l'enregistrement de leur carte de paiement.</p>
    <p>Dans le cadre du Service de Paiement Intégré, l'Utilisateur valide le paiement à l'issue de la réalisation d'un Service qu'il a commandé au moyen d'une des Applications Mobiles&nbsp;; par exemple, sans que cet exemple soit limitatif, au terme d'une session de charge d'un véhicule électrique au moyen d'une Prise Connectée Sparklin. Si à l'issue de l'exécution du Service l'Utilisateur ne valide pas le paiement, cette validation sera réalisée automatiquement après un délai de 24 heures, sauf si l'Utilisateur conteste la bonne exécution de tout ou partie de la Transaction avant l'échéance de ce délai.</p>
    <p>Dans le cadre de l'utilisation du Service de Paiement Intégré, il est nécessaire que la carte ou le moyen de paiement de l'Utilisateur soit valide&nbsp;; Sparklin vérifiera, par tout moyen utile, la validité de la carte ou du moyen de paiement de l'Utilisateur avant d'autoriser l'exécution de la commande du Service. Par ailleurs, il est précisé que le montant du paiement validé par l'Utilisateur sera, dans un premier temps, conservé sur un compte séquestre contrôlé par le Partenaire prestataire de services de paiement choisi par Sparklin&nbsp;; le montant sera définitivement transféré sur un compte contrôlé par Sparklin après la clôture de la Transaction.</p>
    <p>Dans le cadre du Service de Paiement Intégré, la Transaction est clôturée&nbsp;:</p>
    <ul>
      <li>automatiquement après l'expiration de la période de contestation de 24 heures («&nbsp;Période de Contestation&nbsp;») si aucun litige n'est déclaré par l'Utilisateur au moyen de la fonction prévue à cet effet dans l'Application Utilisateur&nbsp;;</li>
      <li>à la clôture du litige par Sparklin, si un litige est déclaré au cours de la Période de Contestation par l'Utilisateur.</li>
    </ul>
    <p>Une fois la Transaction finalisée, le prix du service est immédiatement transféré par le Partenaire prestataire de services de paiement vers Sparklin.</p>
    <p>SPARKLIN ne fournit aucun service de traitement des paiements aux Utilisateurs. Il incombe aux Utilisateurs de fournir les coordonnées exactes des cartes de crédit, des cartes de débit et de tout autre mode de paiement offert sur l'Application Utilisateur. L'Utilisateur sera seul responsable de la transmission des informations liées à sa carte bancaire, et Sparklin décline toute responsabilité s'agissant de la saisie, du traitement, de l'utilisation et de la conservation de ces données dans toute la mesure autorisée par la législation applicable.</p>
    <p>L'attention des Utilisateurs est attirée sur le fait que le droit d'utiliser le Service de Paiement Intégré ne peut être conféré par Sparklin et ses Partenaires que dans le cadre des Transactions effectuées par un Utilisateur en son nom propre. En particulier, et sans que cela soit limitatif, les Utilisateurs ne sont pas autorisés à revendre, engager ou, de toute autre manière, autoriser des tiers à utiliser le Service de Paiement Intégré pour leur transférer les bénéfices d'un Service&nbsp;; un Service étant réputé être délivré exclusivement à l'Utilisateur identifié.</p>
    <p>En cas de suspicion de fraude de quelque nature que ce soit, de déclaration de litige abusive, d'usage non conforme aux conditions des présentes CGU, ou de violation des conditions générales d'un Partenaire, Sparklin se réserve le droit de suspendre immédiatement le Compte de l'Utilisateur concerné ainsi que les Transactions en cours.</p>

    <h2 id="facturation">Facturation</h2>
    <p>Le Gérant donne mandat à SPARKLIN, en vertu duquel, pour chaque transaction effectuée au moyen de l'Application Spark-A, SPARKLIN peut établir, au nom et pour le compte du Gérant, une facture et/ou un reçu concernant des services de recharge fournis aux Utilisateurs, sous réserve que le Gérant y ait indiqué les détails de facturation.</p>
    <p>Le Gérant peut contester les factures et/ou reçus que SPARKLIN a établis en son nom et pour son compte pendant une période maximale de trois (3) jours à compter de la date d'émission. Passé ce délai, le Gérant est réputé avoir validé cette facture et/ou ce reçu.</p>
    <p>Il est entendu que le Gérant effectuera le stockage et l'archivage des factures et/ou reçus conformément aux lois applicables.</p>

    <h2 id="liens">Liens hypertextes</h2>
    <p>L'Application Utilisateur peut contenir des liens hypertextes pointant vers d'autres sites internet sur lesquels Sparklin n'exerce pas de contrôle.</p>
    <p>Dans une telle situation, Sparklin décline toute responsabilité quant aux contenus, publicités, produits, services ou tout autre élément disponible que l'Utilisateur pourra consulter sur ces sites non contrôlés par Sparklin.</p>

    <h2 id="donnees">Données personnelles</h2>
    <p>La Politique de confidentialité, disponible sur le site www.sparklin.io, décrit les Données personnelles des Utilisateurs collectées par Sparklin et les finalités pour lesquelles Sparklin traite ces données. En acceptant les présentes CGU, l'Utilisateur reconnaît avoir pris connaissance de la Politique de confidentialité des Données Personnelles.</p>

    <h2 id="pi">Propriété intellectuelle</h2>
    <p>L'ensemble des éléments constitutifs de l'Application Utilisateur, notamment, mais sans que cela soit limitatif, le design utilisateur, l'organisation de la navigation au sein de l'Application Utilisateur Sparklin, les textes, graphiques, images, photographies, sons, vidéos, représentations en 3 dimensions et autres éléments logiciels qui composent l'Application Utilisateur, sont la propriété de Sparklin et sont à ce titre protégés par les lois en vigueur en France.</p>
    <p>Les présentes CGU n'emportent aucune cession, d'aucune sorte, des droits de propriété intellectuelle sur l'un ou l'autre de ces éléments.</p>
    <p>Toute représentation, reproduction, ingénierie inversée, adaptation ou exploitation partielle ou totale des éléments logiciels, contenus, marques, inventions brevetées, modèles ou de toute autre propriété de Sparklin, par quelque procédé que ce soit, est strictement interdite sans l'autorisation préalable, expresse et écrite de Sparklin, et constituerait, sans cet accord, une contrefaçon au sens des articles L.335-2 et suivants du Code de la propriété intellectuelle.</p>
    <p>L'autorisation donnée par Sparklin à l'Utilisateur d'accéder à l'Application Utilisateur et aux Services Sparklin ne vaut pas reconnaissance d'un droit à l'Utilisateur et, en particulier, ne confère aucun droit de propriété intellectuelle relatif à quelque élément que ce soit de l'Application Utilisateur, lesquels restent la propriété exclusive de Sparklin.</p>
    <p>Le présent Contrat confère à l'Utilisateur, sous réserve du respect plein et entier des CGU et du paiement des sommes dues à Sparklin, un droit d'usage des Applications Mobiles Sparklin, de manière non exclusive, non transférable et non cessible, pour toute la durée des présentes CGU.</p>
    <p>Ce droit d'utilisation s'effectue par accès distant à partir de l'Application Utilisateur et comprend&nbsp;:</p>
    <ul>
      <li>le droit d'accéder à l'Application Utilisateur conformément aux présentes CGU&nbsp;;</li>
      <li>le droit d'accéder aux Services Sparklin conformément aux présentes CGU.</li>
    </ul>
    <p>Il est interdit à l'Utilisateur d'introduire, de combiner ou d'intégrer des composants logiciels ou des données, ou de procéder à toute autre action, par quelque moyen que ce soit, qui modifierait ou serait susceptible de modifier le comportement, le contenu ou l'apparence de l'Application Utilisateur et des Services Sparklin.</p>

    <h2 id="droit">Droit applicable</h2>
    <p>Les présentes Conditions Générales d'Utilisation sont régies par et devront être interprétées selon le droit français.</p>
    <p>En cas de conflit ou de litige survenant à propos de l'exécution, de la résiliation ou des conditions du présent contrat, les parties s'efforceront de régler leur différend à l'amiable.</p>
    <p>Si aucune solution amiable n'était trouvée, le litige serait porté exclusivement devant les juridictions compétentes de Nantes.</p>

    <h2 id="duree">Durée</h2>
    <p>Les présentes CGU sont opposables aux Utilisateurs dès leur acceptation et sont conclues pour une durée indéterminée.</p>
    <p>Il est rappelé que l'Utilisateur peut, à tout moment et quel que soit le motif, se désinscrire et supprimer son Compte Sparklin dans les conditions fixées à l'article «&nbsp;Désinscription — Suspension&nbsp;» des présentes CGU. De même, Sparklin peut être amenée à suspendre ou retirer l'accès de l'Application à l'Utilisateur dans les mêmes conditions.</p>
    <p>La désinscription vaut résiliation des présentes CGU.</p>
    <p>En cas de résiliation par Sparklin, Sparklin en informera l'Utilisateur au moyen de l'adresse de courriel fournie et maintenue à jour par l'Utilisateur dans son Compte Sparklin&nbsp;; si cette adresse n'est pas accessible pour quelque raison que ce soit, Sparklin n'a pas d'autre obligation d'informer l'Utilisateur de la résiliation du présent Contrat.</p>
    <p>Il est précisé que la résiliation du présent Contrat à l'initiative de Sparklin n'entraîne aucun droit supplémentaire ni aucun dédommagement ou autre recours au bénéfice de l'Utilisateur.</p>
    <p>En cas de résiliation par l'Utilisateur, il est rappelé que l'Utilisateur reste redevable et doit s'acquitter dans les plus brefs délais des sommes restant dues à Sparklin, que Sparklin pourra réclamer et recouvrer par tout moyen à sa convenance et aux frais de l'Utilisateur.</p>

    <h2 id="mediation">Notification, réclamation et médiation</h2>
    <p>Toute notification et/ou réclamation devra être effectuée par mail envoyé à&nbsp;: <a href="mailto:dpo@sparklin.io">dpo@sparklin.io</a></p>
    <p>L'Utilisateur, lorsqu'il agit en qualité de consommateur, est informé de la possibilité de recourir, en cas de contestation résultant de l'utilisation de l'Application Utilisateur et des Services Sparklin, à une procédure de médiation conventionnelle ou à tout autre mode alternatif de règlement des différends, dès lors qu'un tel litige n'a pas pu être réglé dans le cadre d'une réclamation préalable directement introduite auprès de Sparklin.</p>
    <p>Sparklin est tenu de communiquer les coordonnées du médiateur qu'il a désigné.</p>
    <p>Pour information, site de la Commission européenne dans le cadre de la médiation&nbsp;: <a href="http://ec.europa.eu/consumers/odr/" target="_blank" rel="noopener">ec.europa.eu/consumers/odr</a></p>

    <h2 id="annexe-a">Annexe A — Services Sparklin</h2>
    <p>La liste des Services Sparklin est la liste des Services qui peuvent être rendus par Sparklin et ses Partenaires aux Utilisateurs Sparklin.</p>
    <p>Sparklin n'a aucune obligation d'offrir l'un quelconque de ses Services à un Utilisateur donné et reste seule partie à ce contrat à pouvoir décider quel Service est offert à quel Utilisateur.</p>
    <p>La liste de ces Services est susceptible d'être modifiée, complétée ou réduite à tout moment à la seule discrétion de Sparklin, sans que ces changements n'entraînent de droits supplémentaires ni de dédommagements ou autres recours au bénéfice des Utilisateurs, ceux-ci restant libres à tout moment de renoncer aux Services Sparklin.</p>
    <p>Liste des Services à la date du 01/04/2023&nbsp;:</p>
    <ul>
      <li>Accès au service de charge pour véhicules électriques&nbsp;;</li>
      <li>Accès à un compte utilisateur&nbsp;;</li>
      <li>Accès à des communautés&nbsp;;</li>
      <li>Lancement d'une session de charge dans ces communautés&nbsp;;</li>
      <li>Suivi de sa session de charge à distance&nbsp;;</li>
      <li>Arrêt de sa session de charge à distance&nbsp;;</li>
      <li>Planification des heures de charge à domicile&nbsp;;</li>
      <li>Paiement en ligne&nbsp;;</li>
      <li>Édition de notes de frais&nbsp;;</li>
      <li>Déclaration de la nature de la session de charge (motif professionnel ou personnel)&nbsp;;</li>
      <li>Consultation de l'historique de charge.</li>
    </ul>

    <h2 id="annexe-b">Annexe B — Présentation des applications mobiles Sparklin</h2>
    <p>La liste des fonctionnalités des Applications Mobiles Sparklin est la liste des fonctionnalités offertes aux Utilisateurs.</p>
    <p>Sparklin n'a aucune obligation d'offrir l'une quelconque de ses fonctionnalités à un Utilisateur donné et reste seule à pouvoir décider quelles fonctionnalités sont offertes aux Utilisateurs.</p>
    <p>La liste de ces fonctionnalités est susceptible de varier entre les applications Spark-A et Spark-i, et également d'être modifiée, complétée ou réduite à tout moment à la seule discrétion de Sparklin, sans que ces changements n'entraînent de droits supplémentaires ni de dédommagements ou autres recours au bénéfice des Utilisateurs, ceux-ci restant libres à tout moment de renoncer aux Services Sparklin.</p>
    <p>À la date du 01/04/2023, les applications Spark-A et Spark-i ont pour fonctionnalités&nbsp;:</p>
    <ul>
      <li>le suivi par un Utilisateur Sparklin de ses droits de consommation et de ses droits d'accès dans la ou les différentes Communautés auxquelles il appartient&nbsp;;</li>
      <li>le paiement ou l'enregistrement, par un Utilisateur Sparklin, du service de chargement du véhicule électrique, selon les tarifs définis dans les Conditions Générales de Vente&nbsp;;</li>
      <li>la validation d'une Transaction par l'Utilisateur&nbsp;;</li>
      <li>l'enregistrement d'un litige au sujet d'une Transaction par l'Utilisateur&nbsp;;</li>
      <li>l'accès à l'historique de charge et aux factures pour un Utilisateur.</li>
    </ul>
  </div>
</section>
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
        <li><a href="/cas/hotel/" data-i18n="footer.lnk.hotel"><?= tr('footer.lnk.hotel') ?></a></li>
        <li><a href="/cas/camping/" data-i18n="footer.lnk.camping"><?= tr('footer.lnk.camping') ?></a></li>
        <li><a href="/cas/collectivite/" data-i18n="footer.lnk.coll"><?= tr('footer.lnk.coll') ?></a></li>
      </ul>
    </div>

    <!-- COL 4 : Ressources -->
    <div class="footer-col">
      <h4 data-i18n="footer.h4"><?= tr('footer.h4') ?></h4>
      <ul>
        <li><a href="/livre-blanc/"><span data-i18n="footer.lnk.lb"><?= tr('footer.lnk.lb') ?></span> <span class="footer-badge" data-i18n="footer.new"><?= tr('footer.new') ?></span></a></li>
        <li><a href="/blog/" data-i18n="blog.label"><?= tr('blog.label') ?></a></li>
        <li><a href="/" data-i18n="footer.lnk.offer"><?= tr('footer.lnk.offer') ?></a></li>
        <li><a href="/a-propos/" data-i18n="footer.lnk.company"><?= tr('footer.lnk.company') ?></a></li>
        <li><a href="/contact/" data-i18n="footer.lnk.contact_us"><?= tr('footer.lnk.contact_us') ?></a></li>
        <li><a href="/support/" data-i18n="footer.lnk.support"><?= tr('footer.lnk.support') ?></a></li>
        <li><a href="/evenements/" data-i18n="footer.lnk.events"><?= tr('footer.lnk.events') ?></a></li>
      </ul>
    </div>

  </div>

  <div class="footer-bottom">
    <span class="footer-bottom-left" data-i18n="footer.legal"><?= tr('footer.legal') ?></span>
    <nav class="footer-bottom-links">
      <a href="/mentions-legales/" data-i18n="footer.lnk.legal"><?= tr('footer.lnk.legal') ?></a>
      <a href="/cgu/" data-i18n="footer.lnk.cgu"><?= tr('footer.lnk.cgu') ?></a>
      <a href="/politique-confidentialite/">Confidentialité</a>
      <a href="/politique-cookies/">Cookies</a>
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
  // Ne se charge qu'apres consentement cookies (voir skCookieChoice / skCookieInit
  // plus bas) : window.skLoadCrisp() est l'unique point d'entree, protege contre
  // un double chargement.
  window.skLoadCrisp = function(){
    if (window.$crisp) return; // deja charge
    window.$crisp=[];
    window.CRISP_WEBSITE_ID="326a0f31-24a5-4709-9538-ff5f4aa65f71";
    var d=document;
    var s=d.createElement("script");
    s.src="https://client.crisp.chat/l.js";
    s.async=1;
    d.getElementsByTagName("head")[0].appendChild(s);
  };
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
      if (window.skLoadCrisp) window.skLoadCrisp();
    }
  }
  window.skCookieChoice = skCookieChoice;
  function skCookieInit() {
    try {
      var consent = localStorage.getItem(COOKIE_KEY);
      if (consent === 'accepted' && window.skLoadCrisp) window.skLoadCrisp();
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
