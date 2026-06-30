<?php
require_once __DIR__ . '/includes/env.php';
require_once __DIR__ . '/includes/supabase.php';
require_once __DIR__ . '/includes/i18n.php';
loadEnv(__DIR__ . '/.env');
$lang = initI18n();
http_response_code(404);

// traduction avec repli FR si la clé n'existe pas encore en base
function e4(string $k, string $fr): string { $v = tr($k); return $v === $k ? $fr : $v; }
?>
<!DOCTYPE html>
<html lang="<?= lang() ?>">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="robots" content="noindex, follow"/>
  <title>404 — Sparklin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@600;700;800&family=Wix+Madefor+Text:wght@400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/assets/css/style.css"/>
  <style>
    .err-wrap{min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;
      text-align:center;padding:40px 20px;background:var(--dark,#0a0a18);color:#fff;font-family:'Wix Madefor Text',sans-serif;}
    .err-code{font-family:'Wix Madefor Display',sans-serif;font-weight:800;font-size:clamp(5rem,18vw,9rem);
      line-height:1;background:linear-gradient(135deg,#FF6F49,#E8563A);-webkit-background-clip:text;
      background-clip:text;-webkit-text-fill-color:transparent;margin-bottom:8px;}
    .err-h1{font-family:'Wix Madefor Display',sans-serif;font-weight:700;font-size:clamp(1.4rem,4vw,2rem);margin:0 0 12px;}
    .err-text{color:rgba(255,255,255,.6);max-width:440px;line-height:1.6;margin:0 0 28px;}
    .err-btn{display:inline-block;background:linear-gradient(135deg,#FF6F49,#E8563A);color:#fff;text-decoration:none;
      font-weight:600;padding:14px 28px;border-radius:12px;transition:transform .15s,box-shadow .15s;}
    .err-btn:hover{transform:translateY(-2px);box-shadow:0 12px 32px rgba(232,86,58,.35);}
  </style>
</head>
<body>
  <div class="err-wrap">
    <div class="err-code">404</div>
    <h1 class="err-h1"><?= e4('err404.h1', 'Page introuvable') ?></h1>
    <p class="err-text"><?= e4('err404.text', "La page que vous cherchez n'existe pas ou a été déplacée.") ?></p>
    <a class="err-btn" href="/"><?= e4('err404.cta', "Retour à l'accueil") ?></a>
  </div>
</body>
</html>
