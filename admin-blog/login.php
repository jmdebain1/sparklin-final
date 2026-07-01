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
<meta name="viewport" content="width=device-width,initial-scale=1"/>
<meta name="robots" content="noindex,nofollow"/>
<title>Connexion — Sparklin Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@600;700;800&family=Wix+Madefor+Text:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#F7F6F2;
  --white:#fff;
  --border:#E4E2DC;
  --orange:#E8563A;
  --orange2:#C94B31;
  --dark:#1A1A2E;
  --text:#1A1916;
  --text2:#4A4844;
  --text3:#8A8780;
  --green:#15803D;
  --green-bg:#F0FDF4;
  --red:#DC2626;
  --red-bg:#FEF2F2;
  --r:12px;
  --shadow:0 4px 24px rgba(0,0,0,.08),0 1px 3px rgba(0,0,0,.04);
}
html,body{
  height:100%;
  font-family:'Wix Madefor Text',system-ui,sans-serif;
  background:var(--bg);
  color:var(--text);
}

/* ── LAYOUT ── */
.page{
  min-height:100vh;
  display:grid;
  grid-template-columns:1fr 1fr;
}

/* ── LEFT (brand) ── */
.brand-panel{
  background:var(--dark);
  display:flex;
  flex-direction:column;
  justify-content:space-between;
  padding:48px;
  position:relative;
  overflow:hidden;
}
.brand-panel::before{
  content:'';
  position:absolute;
  left:0;top:0;bottom:0;
  width:4px;
  background:var(--orange);
}

/* Dots décoratifs */
.brand-dots{
  position:absolute;
  top:40px;right:40px;
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:10px;
}
.brand-dot{
  width:6px;height:6px;
  border-radius:50%;
  background:var(--orange);
}
.brand-dot:nth-child(even){opacity:.35;}
.brand-dot:nth-child(3n){opacity:.6;}

.brand-logo{
  font-family:'Wix Madefor Display',sans-serif;
  font-size:28px;
  font-weight:800;
  color:var(--orange);
  letter-spacing:-.02em;
  position:relative;z-index:1;
}
.brand-badge{
  display:inline-flex;
  align-items:center;
  gap:6px;
  font-size:10px;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:.1em;
  color:rgba(255,255,255,.4);
  margin-top:4px;
}
.brand-badge::before{
  content:'';
  width:4px;height:4px;border-radius:50%;
  background:rgba(255,255,255,.3);
}

.brand-content{
  position:relative;z-index:1;
}
.brand-title{
  font-family:'Wix Madefor Display',sans-serif;
  font-size:2.2rem;
  font-weight:800;
  color:#fff;
  line-height:1.15;
  margin-bottom:16px;
  letter-spacing:-.03em;
}
.brand-title em{
  font-style:normal;
  color:var(--orange);
}
.brand-desc{
  font-size:14px;
  color:rgba(255,255,255,.5);
  line-height:1.75;
  font-weight:300;
  max-width:340px;
}

.brand-features{
  display:flex;
  flex-direction:column;
  gap:12px;
  position:relative;z-index:1;
}
.brand-feat{
  display:flex;
  align-items:center;
  gap:10px;
  font-size:12px;
  color:rgba(255,255,255,.55);
}
.feat-dot{
  width:5px;height:5px;border-radius:50%;
  background:var(--orange);flex-shrink:0;
}

.brand-footer{
  font-size:11px;
  color:rgba(255,255,255,.2);
  position:relative;z-index:1;
  font-family:'Wix Madefor Text',monospace;
}

/* ── RIGHT (form) ── */
.form-panel{
  display:flex;
  align-items:center;
  justify-content:center;
  padding:48px;
  background:var(--white);
}
.form-box{
  width:100%;
  max-width:380px;
}
.form-header{
  margin-bottom:36px;
}
.form-label{
  font-size:11px;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:.1em;
  color:var(--orange);
  margin-bottom:10px;
}
.form-title{
  font-family:'Wix Madefor Display',sans-serif;
  font-size:1.7rem;
  font-weight:800;
  color:var(--text);
  line-height:1.2;
  letter-spacing:-.03em;
  margin-bottom:8px;
}
.form-sub{
  font-size:13px;
  color:var(--text3);
  line-height:1.6;
}

/* ── INPUT ── */
.input-group{
  display:flex;
  flex-direction:column;
  gap:6px;
  margin-bottom:16px;
}
.input-label{
  font-size:12px;
  font-weight:600;
  color:var(--text2);
}
.input-wrap{
  position:relative;
}
.input-wrap svg{
  position:absolute;
  left:14px;top:50%;
  transform:translateY(-50%);
  color:var(--text3);
  pointer-events:none;
}
input[type="email"]{
  width:100%;
  padding:12px 14px 12px 42px;
  border:1.5px solid var(--border);
  border-radius:8px;
  font-family:'Wix Madefor Text',sans-serif;
  font-size:14px;
  color:var(--text);
  background:var(--bg);
  outline:none;
  transition:border-color .15s,background .15s;
}
input[type="email"]:focus{
  border-color:var(--orange);
  background:var(--white);
}
input[type="email"]::placeholder{color:var(--text3)}

.btn-submit{
  width:100%;
  padding:13px;
  background:var(--orange);
  color:#fff;
  border:none;
  border-radius:8px;
  font-family:'Wix Madefor Text',sans-serif;
  font-size:14px;
  font-weight:700;
  cursor:pointer;
  transition:background .15s,transform .1s;
  display:flex;
  align-items:center;
  justify-content:center;
  gap:8px;
  margin-bottom:20px;
}
.btn-submit:hover{background:var(--orange2);}
.btn-submit:active{transform:scale(.99);}
.btn-submit:disabled{background:#ccc;cursor:not-allowed;transform:none;}

/* ── SPINNER ── */
.spinner{
  width:16px;height:16px;
  border:2px solid rgba(255,255,255,.3);
  border-top-color:#fff;
  border-radius:50%;
  animation:spin .7s linear infinite;
}
@keyframes spin{to{transform:rotate(360deg)}}

/* ── STATES ── */
.notice{
  display:none;
  padding:12px 14px;
  border-radius:8px;
  font-size:13px;
  line-height:1.55;
  margin-bottom:16px;
}
.notice.show{display:flex;align-items:flex-start;gap:10px;}
.notice-success{background:var(--green-bg);color:var(--green);border:1px solid rgba(21,128,61,.2);}
.notice-error{background:var(--red-bg);color:var(--red);border:1px solid rgba(220,38,38,.15);}
.notice svg{flex-shrink:0;margin-top:1px;}

/* ── SUCCESS STATE ── */
.success-state{
  display:none;
  flex-direction:column;
  align-items:center;
  text-align:center;
  gap:16px;
}
.success-state.show{display:flex;}
.success-icon{
  width:64px;height:64px;
  border-radius:50%;
  background:var(--green-bg);
  display:flex;align-items:center;justify-content:center;
}
.success-title{
  font-family:'Wix Madefor Display',sans-serif;
  font-size:1.3rem;
  font-weight:700;
  color:var(--text);
}
.success-sub{
  font-size:13px;
  color:var(--text3);
  line-height:1.7;
  max-width:280px;
}
.success-email{
  font-weight:600;
  color:var(--orange);
}
.resend-btn{
  font-size:12px;
  color:var(--text3);
  background:none;
  border:1px solid var(--border);
  border-radius:6px;
  padding:6px 14px;
  cursor:pointer;
  font-family:'Wix Madefor Text',sans-serif;
  transition:all .15s;
  margin-top:8px;
}
.resend-btn:hover{border-color:var(--orange);color:var(--orange);}

/* ── FORM HINT ── */
.form-hint{
  font-size:11px;
  color:var(--text3);
  text-align:center;
  line-height:1.6;
}

/* ── RESPONSIVE ── */
@media(max-width:768px){
  .page{grid-template-columns:1fr;}
  .brand-panel{display:none;}
  .form-panel{padding:32px 24px;}
}
</style>
</head>
<body>

<div class="page">

  <!-- ══ BRAND PANEL ════════════════════════════════════════ -->
  <div class="brand-panel">
    <div class="brand-dots">
      <div class="brand-dot"></div><div class="brand-dot"></div><div class="brand-dot"></div><div class="brand-dot"></div>
      <div class="brand-dot"></div><div class="brand-dot"></div><div class="brand-dot"></div><div class="brand-dot"></div>
      <div class="brand-dot"></div><div class="brand-dot"></div><div class="brand-dot"></div><div class="brand-dot"></div>
    </div>

    <div>
      <div class="brand-logo">sparklin</div>
      <div class="brand-badge">Espace rédaction</div>
    </div>

    <div class="brand-content">
      <h2 class="brand-title">
        Votre outil de<br>
        rédaction <em>assistée<br>par IA.</em>
      </h2>
      <p class="brand-desc">
        Rédigez, générez et publiez vos articles IRVE directement depuis ce tableau de bord — sans coder.
      </p>
    </div>

    <div class="brand-features">
      <div class="brand-feat"><div class="feat-dot"></div>Génération d'articles avec Claude IA</div>
      <div class="brand-feat"><div class="feat-dot"></div>Score SEO en temps réel</div>
      <div class="brand-feat"><div class="feat-dot"></div>Planification et publication directe</div>
      <div class="brand-feat"><div class="feat-dot"></div>Historique & restauration</div>
    </div>

    <div class="brand-footer">sparklin.io · admin-blog v2.0</div>
  </div>

  <!-- ══ FORM PANEL ══════════════════════════════════════════ -->
  <div class="form-panel">
    <div class="form-box">

      <!-- FORM STATE -->
      <div id="form-state">
        <div class="form-header">
          <div class="form-label">Connexion sécurisée</div>
          <h1 class="form-title">Accéder à<br>l'espace admin</h1>
          <p class="form-sub">Entrez votre adresse email autorisée. Vous recevrez un lien de connexion valable <strong>15 minutes</strong>.</p>
        </div>

        <!-- Notice -->
        <div class="notice notice-error" id="notice-error">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="error-msg">Une erreur est survenue.</span>
        </div>

        <!-- Email input -->
        <div class="input-group">
          <label class="input-label" for="email-input">Adresse email</label>
          <div class="input-wrap">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input type="email" id="email-input"
              placeholder="vous@sparklin.io"
              autocomplete="email"
              inputmode="email"
              required/>
          </div>
        </div>

        <!-- Submit -->
        <button class="btn-submit" id="submit-btn" onclick="handleSubmit()">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
          Envoyer le lien magique
        </button>

        <p class="form-hint">
          Accès réservé aux rédacteurs autorisés.<br>
          Mode démo — entrez votre email pour accéder.
        </p>
      </div>

      <!-- SUCCESS STATE -->
      <div class="success-state" id="success-state">
        <div class="success-icon">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2.2" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="success-title">Vérifiez votre boîte mail</div>
        <p class="success-sub">
          Un lien de connexion a été envoyé à<br>
          <span class="success-email" id="success-email"></span><br><br>
          Cliquez sur le lien pour accéder à l'espace rédaction. Il expirera dans <strong>15 minutes</strong>.
        </p>
        <p class="success-sub" style="font-size:12px;opacity:.7;">
          Vous pouvez fermer cet onglet.
        </p>
        <button class="resend-btn" id="resend-btn" onclick="resetForm()">
          ← Modifier l'adresse email
        </button>
      </div>

    </div>
  </div>
</div>

<script>
var sending = false;
var FUNCTION_URL = '/api/send-magic-link.php';

function showError(msg) {
  var notice = document.getElementById('notice-error');
  document.getElementById('error-msg').textContent = msg;
  notice.classList.add('show');
}
function hideError() {
  document.getElementById('notice-error').classList.remove('show');
}

function setLoading(loading) {
  var btn = document.getElementById('submit-btn');
  var input = document.getElementById('email-input');
  sending = loading;
  input.disabled = loading;
  btn.disabled = loading;
  btn.innerHTML = loading
    ? '<div class="spinner"></div> Envoi en cours…'
    : '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg> Envoyer le lien magique';
}

async function handleSubmit() {
  if (sending) return;
  hideError();

  var email = document.getElementById('email-input').value.trim().toLowerCase();

  if (!email) {
    showError('Veuillez entrer une adresse email.');
    document.getElementById('email-input').focus();
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    showError('Format d\'email invalide.');
    return;
  }

  setLoading(true);

  // Envoi réel du lien magique. On affiche toujours l'état "succès"
  // (on ne révèle pas si l'email est autorisé). Pas de session ici :
  // l'utilisateur clique le lien reçu par email → /admin-blog/?token=...
  try {
    await fetch(FUNCTION_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email })
    });
  } catch (err) {
    /* on reste silencieux et on affiche quand même l'état succès */
  }
  setLoading(false);
  document.getElementById('success-email').textContent = email;
  document.getElementById('form-state').style.display = 'none';
  document.getElementById('success-state').classList.add('show');
}

function resetForm() {
  document.getElementById('success-state').classList.remove('show');
  document.getElementById('form-state').style.display = '';
  document.getElementById('email-input').value = '';
  document.getElementById('email-input').focus();
}

// Enter key support
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('email-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') handleSubmit();
  });
  document.getElementById('email-input').focus();
});
</script>

</body>
</html>
