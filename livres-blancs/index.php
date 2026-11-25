<?php
/**
 * PRÉ-PROD — page « Ressources · Livres blancs ».
 * Volontairement hors sitemap, noindex, non liée dans la navigation.
 * Chaque formulaire envoie { guide } à /.netlify/functions/send-livre-blanc, qui
 * inscrit le contact dans la liste Brevo du guide et envoie le mail (template sectoriel).
 */
require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/i18n.php';
loadEnv(__DIR__ . '/../.env');
$lang = initI18n();
$rcSite = $_ENV['RECAPTCHA_SITE_KEY'] ?? '';

$guides = [
  [
    'key' => 'entreprises', 'sector' => 'Entreprises', 'pages' => 16,
    'title' => 'Bornes de recharge en entreprise : le guide complet 2026',
    'hook'  => 'Tout ce que votre entreprise doit savoir avant de déployer des bornes de recharge.',
    'points' => ['Réglementation LOM, Décret Tertiaire et quotas de parking', 'Load balancing, OCPP et interopérabilité GIREVE', 'Fiscalité, remboursement URSSAF et financement Advenir'],
  ],
  [
    'key' => 'restaurants', 'sector' => 'Restaurants', 'pages' => 17,
    'title' => 'Le guide pour installer des bornes de recharge dans votre restaurant',
    'hook'  => 'Un repas dure 45 min à 2 h : exactement le temps d’une recharge.',
    'points' => ['Recharge en temps masqué : attirer et fidéliser', 'Obligations LOM et calendrier', 'Modèles économiques et financement'],
  ],
  [
    'key' => 'hotels', 'sector' => 'Hôtels', 'pages' => 16,
    'title' => 'Le guide pour installer des bornes de recharge dans votre hôtel',
    'hook'  => 'Sur Booking, le filtre « borne de recharge » écarte déjà les hôtels non équipés.',
    'points' => ['Apparaître là où le voyageur électrique cherche', 'Supervision, facturation et maintenance', 'Modèles économiques et financement'],
  ],
  [
    'key' => 'gites', 'sector' => 'Gîtes &amp; locations saisonnières', 'pages' => 16,
    'title' => 'Le guide pour installer une borne de recharge dans votre gîte ou location saisonnière',
    'hook'  => 'Airbnb et Booking proposent un filtre « borne de recharge » : soyez dans les résultats.',
    'points' => ['Gagner en visibilité sur Airbnb et Booking', 'Quelle borne choisir, à quel coût', 'Financer et rentabiliser son projet'],
  ],
  [
    'key' => 'golf', 'sector' => 'Golf', 'pages' => 16,
    'title' => 'Le guide pour installer des bornes de recharge dans votre golf',
    'hook'  => 'Un parking plein pendant 4 à 5 heures : une opportunité en or.',
    'points' => ['Une clientèle déjà électrique', 'Ce que dit la loi, bornes adaptées à un club', 'Financer votre projet'],
  ],
  [
    'key' => 'collectivites', 'sector' => 'Collectivités', 'pages' => 25,
    'title' => 'La recharge électrique, enjeu stratégique pour les collectivités',
    'hook'  => 'Le guide à l’usage des maires et des décideurs locaux.',
    'points' => ['Cadre réglementaire décrypté (LOM, DPEB)', 'Quel modèle juridique pour votre territoire ?', 'Financer et déployer efficacement'],
  ],
];
$guidesJs = [];
foreach ($guides as $g) {
  $guidesJs[$g['key']] = [
    'sector' => html_entity_decode($g['sector']),
    'title'  => $g['title'],
    'pdf'    => '/assets/livres-blancs/livre-blanc-sparklin-' . $g['key'] . '-2026.pdf',
    'cover'  => '/assets/images/livres-blancs/mockup-' . $g['key'] . '.webp',
  ];
}
?>
<!DOCTYPE html>
<html lang="<?= lang() ?>">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Livres blancs par secteur — Sparklin</title>
  <meta name="description" content="Téléchargez gratuitement nos livres blancs : installer des bornes de recharge dans un restaurant, un hôtel, un gîte, un golf ou une collectivité."/>
  <meta name="robots" content="noindex,nofollow"/>
  <link rel="icon" href="/favicon.ico" sizes="any"/>
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png"/>
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;500;600;700;800&family=Wix+Madefor+Text:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/assets/css/style.css">
  <style>
    .lbx-hero{background:var(--dark);padding:96px clamp(20px,5vw,80px) 72px;text-align:center}
    .lbx-hero h1{font-family:var(--font-display);font-size:clamp(2rem,4vw,3.1rem);font-weight:800;color:#fff;line-height:1.12;margin:0 auto 16px;max-width:820px}
    .lbx-hero h1 em{color:var(--orange);font-style:normal}
    .lbx-hero p{font-size:16px;line-height:1.7;color:rgba(255,255,255,.62);max-width:620px;margin:0 auto 26px}
    .lbx-chips{display:flex;flex-wrap:wrap;justify-content:center;gap:8px}
    .lbx-chips a{background:rgba(255,255,255,.1);color:rgba(255,255,255,.78);font-size:13px;padding:6px 14px;border-radius:100px;text-decoration:none;transition:background .15s}
    .lbx-chips a:hover{background:rgba(232,86,58,.35);color:#fff}
    .lbx-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(min(100%,420px),1fr));gap:28px}
    .lbx-card{display:flex;flex-direction:column;background:#fff;border:1px solid var(--border);border-radius:20px;overflow:hidden;scroll-margin-top:90px;transition:box-shadow .2s,transform .2s}
    .lbx-card:hover{box-shadow:0 18px 50px rgba(26,26,46,.1);transform:translateY(-3px)}
    .lbx-visual{background:linear-gradient(180deg,#fbf7f5 0%,#fff 100%);padding:18px 18px 0;display:flex;justify-content:center}
    .lbx-visual img{display:block;width:100%;max-width:400px;height:auto;aspect-ratio:1/1;object-fit:contain}
    .lbx-body{display:flex;flex-direction:column;gap:12px;padding:8px 26px 26px;flex:1}
    .lbx-tag{align-self:flex-start;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--orange);background:rgba(232,86,58,.1);padding:4px 11px;border-radius:100px}
    .lbx-body h2{font-family:var(--font-display);font-size:1.18rem;font-weight:800;line-height:1.3;color:var(--dark);margin:0}
    .lbx-hook{font-size:14px;line-height:1.6;color:var(--text-mid);margin:0}
    .lbx-body ul{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:7px}
    .lbx-body li{font-size:13.5px;line-height:1.5;color:var(--text-mid);padding-left:22px;position:relative}
    .lbx-body li::before{content:"";position:absolute;left:0;top:.45em;width:12px;height:12px;border-radius:50%;background:rgba(232,86,58,.16) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12'%3E%3Cpath d='M3 6.2l2 2 4-4.2' fill='none' stroke='%23E8563A' stroke-width='1.6' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") center/12px no-repeat}
    .lbx-meta{font-size:12px;color:var(--text-light);margin-top:auto;padding-top:6px}
    .lbx-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;background:var(--orange);color:#fff;border:none;padding:13px 22px;border-radius:12px;font-family:inherit;font-size:15px;font-weight:700;cursor:pointer;transition:background .15s,transform .15s}
    .lbx-btn:hover{background:#c94b31;transform:translateY(-1px)}
    .lbx-btn[disabled]{opacity:.65;cursor:wait}
    .lbx-modal{position:fixed;inset:0;z-index:500;display:none;align-items:center;justify-content:center;padding:20px;background:rgba(10,10,24,.62);backdrop-filter:blur(4px)}
    .lbx-modal.open{display:flex}
    .lbx-dialog{position:relative;background:#fff;border-radius:22px;max-width:760px;width:100%;max-height:calc(100vh - 40px);overflow:auto;display:grid;grid-template-columns:.9fr 1.1fr;box-shadow:0 40px 100px rgba(0,0,0,.35)}
    .lbx-dialog-visual{background:linear-gradient(180deg,#fbf7f5,#fff);display:flex;align-items:center;justify-content:center;padding:18px}
    .lbx-dialog-visual img{width:100%;height:auto;display:block}
    .lbx-dialog-form{padding:34px 30px 28px;display:flex;flex-direction:column;gap:14px}
    .lbx-dialog-form h3{font-family:var(--font-display);font-size:1.3rem;font-weight:800;line-height:1.25;color:var(--dark);margin:0}
    .lbx-dialog-form .sub{font-size:13.5px;color:var(--text-mid);margin:0;line-height:1.55}
    .lbx-dialog-form label{display:block;font-size:12px;font-weight:600;color:var(--text-mid);margin-bottom:5px}
    .lbx-dialog-form input{width:100%;padding:12px 15px;border:1.5px solid var(--border);border-radius:10px;font-family:inherit;font-size:14px;outline:none;box-sizing:border-box}
    .lbx-dialog-form input:focus{border-color:var(--orange)}
    .lbx-close{position:absolute;top:12px;right:14px;width:34px;height:34px;border-radius:50%;border:none;background:rgba(26,26,46,.07);font-size:18px;line-height:1;cursor:pointer;color:var(--dark)}
    .lbx-err{display:none;background:#FEF2F2;color:#DC2626;border:1px solid rgba(220,38,38,.2);border-radius:8px;padding:9px 13px;font-size:13px}
    .lbx-priv{font-size:11px;line-height:1.5;color:var(--text-light);margin:0}
    .lbx-priv a{color:var(--orange)}
    .lbx-ok{display:none;flex-direction:column;gap:14px;align-items:flex-start}
    .lbx-ok .tick{width:46px;height:46px;border-radius:50%;background:rgba(34,160,90,.14);color:#1c8a4d;display:flex;align-items:center;justify-content:center;font-size:24px}
    .lbx-ok a.dl{display:inline-flex;align-items:center;gap:8px;color:var(--orange);font-weight:700;font-size:14px;text-decoration:none}
    @media(max-width:700px){.lbx-dialog{grid-template-columns:1fr}.lbx-dialog-visual{display:none}.lbx-dialog-form{padding:30px 22px 24px}}
    @keyframes lbx-spin{to{transform:rotate(360deg)}}
  </style>
</head>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<main style="padding-top:64px;">
  <header class="lbx-hero">
    <span style="display:inline-block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:var(--orange);margin-bottom:16px;">Ressources · Livres blancs</span>
    <h1>Votre guide pour installer des bornes de recharge, <em>secteur par secteur.</em></h1>
    <p>Réglementation, choix des bornes, installation, supervision et financement : un guide gratuit, écrit pour votre métier.</p>
    <nav class="lbx-chips" aria-label="Aller à un guide">
      <?php foreach ($guides as $g): ?><a href="#guide-<?= $g['key'] ?>"><?= $g['sector'] ?></a><?php endforeach; ?>
    </nav>
  </header>

  <section style="padding:72px clamp(20px,5vw,80px) 40px;">
    <div class="sk-wrap">
      <div class="lbx-grid">
        <?php foreach ($guides as $g): ?>
        <article class="lbx-card" id="guide-<?= $g['key'] ?>">
          <div class="lbx-visual"><img src="/assets/images/livres-blancs/mockup-<?= $g['key'] ?>.webp" alt="Couverture du livre blanc Sparklin — <?= strip_tags($g['sector']) ?>" width="640" height="640" loading="lazy"/></div>
          <div class="lbx-body">
            <span class="lbx-tag"><?= $g['sector'] ?></span>
            <h2><?= htmlspecialchars($g['title']) ?></h2>
            <p class="lbx-hook"><?= htmlspecialchars($g['hook']) ?></p>
            <ul><?php foreach ($g['points'] as $pt): ?><li><?= htmlspecialchars($pt) ?></li><?php endforeach; ?></ul>
            <div class="lbx-meta">PDF · <?= (int)$g['pages'] ?> pages · Édition 2026 · Gratuit</div>
            <button type="button" class="lbx-btn" onclick="lbxOpen('<?= $g['key'] ?>')">📥 Télécharger le guide</button>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section style="padding:24px clamp(20px,5vw,80px) 90px;">
    <div class="sk-wrap" style="background:var(--bg-off);border-radius:20px;padding:34px clamp(22px,4vw,48px);display:flex;flex-wrap:wrap;gap:20px;align-items:center;justify-content:space-between;">
      <div style="max-width:560px;">
        <h3 style="font-family:var(--font-display);font-size:1.25rem;font-weight:800;color:var(--dark);margin:0 0 6px;">Votre secteur n’est pas dans la liste&nbsp;?</h3>
        <p style="font-size:14px;color:var(--text-mid);margin:0;line-height:1.6;">Parlez-nous de votre site : nous dimensionnons l’installation et chiffrons le projet avec vous. </p>
      </div>
      <div style="display:flex;flex-wrap:wrap;gap:10px;">
        <a class="btn-primary" href="/contact/">Parler à un expert →</a>
      </div>
    </div>
  </section>
</main>

<!-- ══ MODALE DE TÉLÉCHARGEMENT ══ -->
<div class="lbx-modal" id="lbx-modal" role="dialog" aria-modal="true" aria-labelledby="lbx-title" onclick="if(event.target===this)lbxClose()">
  <div class="lbx-dialog">
    <button type="button" class="lbx-close" onclick="lbxClose()" aria-label="Fermer">×</button>
    <div class="lbx-dialog-visual"><img id="lbx-cover" src="" alt=""/></div>
    <div class="lbx-dialog-form">
      <div id="lbx-form-wrap" style="display:flex;flex-direction:column;gap:14px;">
        <span class="lbx-tag" id="lbx-sector" style="align-self:flex-start"></span>
        <h3 id="lbx-title"></h3>
        <p class="sub">Recevez le guide par e-mail, et téléchargez-le immédiatement.</p>
        <form id="lbx-form" style="display:flex;flex-direction:column;gap:12px;" novalidate>
          <div><label for="lbx-email">E-mail professionnel *</label><input id="lbx-email" type="email" name="email" required autocomplete="email" placeholder="vous@entreprise.fr"/></div>
          <div><label for="lbx-prenom">Prénom</label><input id="lbx-prenom" type="text" name="prenom" autocomplete="given-name" placeholder="Jean"/></div>
          <div><label for="lbx-company">Établissement / organisation</label><input id="lbx-company" type="text" name="company" autocomplete="organization" placeholder="Nom de votre structure"/></div>
          <div class="lbx-err" id="lbx-err"></div>
          <button type="submit" class="lbx-btn" id="lbx-btn">📥 Recevoir le guide</button>
          <p class="lbx-priv">En validant, vous acceptez que vos données (e-mail, prénom, établissement) soient utilisées par Sparklin pour vous envoyer ce document et, le cas échéant, vous informer de ses offres. Voir notre <a href="/politique-confidentialite/">politique de confidentialité</a>.</p>
        </form>
      </div>
      <div class="lbx-ok" id="lbx-ok">
        <div class="tick">✓</div>
        <h3>C’est parti&nbsp;! Vérifiez votre boîte mail.</h3>
        <p class="sub">Le guide vient d’être envoyé à <strong id="lbx-ok-mail"></strong>. Pensez à regarder vos courriers indésirables si besoin.</p>
        <a class="dl" id="lbx-dl" href="#" download target="_blank" rel="noopener">📄 Télécharger le PDF maintenant</a>
        <button type="button" class="lbx-btn" style="background:transparent;color:var(--dark);border:1.5px solid var(--border)" onclick="lbxClose()">Fermer</button>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
<?php if ($rcSite): ?><script src="https://www.google.com/recaptcha/api.js?render=<?= htmlspecialchars($rcSite, ENT_QUOTES) ?>"></script><?php endif; ?>
<script>
(function(){
  var GUIDES = <?= json_encode($guidesJs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
  var RC_KEY = <?= json_encode($rcSite) ?>;
  var current = null, lastFocus = null;
  var $ = function(id){ return document.getElementById(id); };

  function recaptcha(action){
    if(!RC_KEY || typeof grecaptcha==='undefined') return Promise.resolve('');
    return new Promise(function(res){ try{ grecaptcha.ready(function(){ grecaptcha.execute(RC_KEY,{action:action}).then(res).catch(function(){res('');}); }); }catch(e){ res(''); } });
  }

  window.lbxOpen = function(key){
    var g = GUIDES[key]; if(!g) return;
    current = key; lastFocus = document.activeElement;
    $('lbx-sector').textContent = g.sector;
    $('lbx-title').textContent = g.title;
    $('lbx-cover').src = g.cover; $('lbx-cover').alt = 'Couverture — ' + g.sector;
    $('lbx-dl').href = g.pdf;
    $('lbx-form-wrap').style.display = 'flex'; $('lbx-ok').style.display = 'none';
    $('lbx-err').style.display = 'none';
    $('lbx-modal').classList.add('open'); document.body.style.overflow = 'hidden';
    setTimeout(function(){ $('lbx-email').focus(); }, 60);
  };
  window.lbxClose = function(){
    $('lbx-modal').classList.remove('open'); document.body.style.overflow = '';
    if(lastFocus && lastFocus.focus) lastFocus.focus();
  };
  document.addEventListener('keydown', function(e){ if(e.key==='Escape' && $('lbx-modal').classList.contains('open')) lbxClose(); });

  $('lbx-form').addEventListener('submit', async function(e){
    e.preventDefault();
    var email = $('lbx-email').value.trim(), err = $('lbx-err'), btn = $('lbx-btn');
    if(!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){ err.textContent = 'Merci de saisir une adresse e-mail valide.'; err.style.display = 'block'; $('lbx-email').focus(); return; }
    err.style.display = 'none';
    var label = btn.innerHTML; btn.disabled = true;
    btn.innerHTML = '<span style="display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:lbx-spin .7s linear infinite;"></span> Envoi…';
    try{
      var token = await recaptcha('livre_blanc');
      var resp = await fetch('/.netlify/functions/send-livre-blanc', {
        method: 'POST', headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ guide: current, email: email, prenom: $('lbx-prenom').value.trim(), company: $('lbx-company').value.trim(), recaptcha_token: token })
      });
      var data = {}; try{ data = await resp.json(); }catch(x){}
      if(!resp.ok){ err.textContent = data.error || 'Une erreur est survenue, merci de réessayer.'; err.style.display = 'block'; btn.disabled = false; btn.innerHTML = label; return; }
      window.dataLayer = window.dataLayer || [];
      window.dataLayer.push({event:'form_submission', form_name:'livre_blanc_'+current});
      if(typeof gtag==='function') gtag('event','lead_form_submit',{form_name:'livre_blanc_'+current});
      $('lbx-ok-mail').textContent = email;
      $('lbx-form-wrap').style.display = 'none'; $('lbx-ok').style.display = 'flex';
      $('lbx-form').reset();
    }catch(ex){
      err.textContent = 'Connexion impossible, merci de réessayer.'; err.style.display = 'block';
    }
    btn.disabled = false; btn.innerHTML = label;
  });
})();
</script>
</body>
</html>
