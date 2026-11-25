/* admin-i18n.js — interface admin (admin-blog) FR → EN.
   Le français reste la langue du code : quand l'interface est en anglais, ce
   module traduit à la volée tous les textes visibles (nœuds texte, placeholders,
   title/aria-label/alt) y compris ceux injectés plus tard par le JS (toasts,
   listes, tableaux), et retraduit en français au retour. Le contenu des articles
   (éditeur, titres) n'est jamais touché. */
(function () {
  'use strict';

  var EXACT = Object.create(null);
  var PATTERNS = [];
  var lang = 'fr';
  var textStore = new WeakMap();
  var attrStore = new WeakMap();
  var ATTRS = ['placeholder', 'title', 'aria-label', 'alt', 'data-ph'];
  var SKIP = 'script,style,noscript,textarea,#rte-editor,#article-preview-frame,.art-title-cell,[data-no-i18n]';

  function norm(s) { return s.replace(/\s+/g, ' ').trim(); }

  function extend(pairs) {
    Object.keys(pairs).forEach(function (k) { EXACT[norm(k)] = pairs[k]; });
  }

  extend({
    'Sparklin — Espace Rédaction': 'Sparklin — Editorial Workspace',

    /* ── login ── */
    'Connexion — Sparklin Admin': 'Sign in — Sparklin Admin',
    'Espace rédaction': 'Editorial workspace',
    'Votre outil de': 'Your AI-assisted',
    'rédaction': 'writing',
    'assistée': 'tool',
    'par IA.': '.',
    'Rédigez, générez et publiez vos articles IRVE directement depuis ce tableau de bord — sans coder.': 'Write, generate and publish your EV charging articles straight from this dashboard — no coding needed.',
    "Génération d'articles avec Claude IA": 'Article generation with Claude AI',
    'Score SEO en temps réel': 'Real-time SEO score',
    'Planification et publication directe': 'Scheduling and direct publishing',
    'Historique & restauration': 'History & restore',
    'Connexion sécurisée': 'Secure sign-in',
    'Accéder à': 'Access the',
    "l'espace admin": 'admin area',
    'Entrez votre adresse email autorisée. Vous recevrez un lien de connexion valable': 'Enter your authorised email address. You will receive a sign-in link valid for',
    '15 minutes': '15 minutes',
    'Une erreur est survenue.': 'An error occurred.',
    'Adresse email': 'Email address',
    'Envoyer le lien magique': 'Send magic link',
    'Accès réservé aux rédacteurs autorisés.': 'Access restricted to authorised editors.',
    'Mode démo — entrez votre email pour accéder.': 'Demo mode — enter your email to sign in.',
    'Vérifiez votre boîte mail': 'Check your inbox',
    'Un lien de connexion a été envoyé à': 'A sign-in link was sent to',
    "Cliquez sur le lien pour accéder à l'espace rédaction. Il expirera dans": 'Click the link to access the editorial workspace. It will expire in',
    'Vous pouvez fermer cet onglet.': 'You can close this tab.',
    "← Modifier l'adresse email": '← Change email address',
    'Veuillez entrer une adresse email.': 'Please enter an email address.',
    "Format d'email invalide.": 'Invalid email format.',
    'Vérification de la session…': 'Checking session…',

    /* ── editor toolbar & placeholders ── */
    'Annuler (Ctrl+Z)': 'Undo (Ctrl+Z)',
    'Rétablir (Ctrl+Y)': 'Redo (Ctrl+Y)',
    'Lien (Ctrl+K)': 'Link (Ctrl+K)',
    'Retirer le lien': 'Remove link',
    'Effacer la mise en forme': 'Clear formatting',
    'Souligné (Ctrl+U)': 'Underline (Ctrl+U)',
    'Rétablir (Ctrl+Shift+Z)': 'Redo (Ctrl+Shift+Z)',
    '✨ Générer auto': '✨ Auto-generate',
    '⏳ Génération…': '⏳ Generating…',
    "Génère extrait, méta description et mot-clé à partir de l'article": 'Generates excerpt, meta description and focus keyword from the article',
    'Ordinateur': 'Desktop',
    'Tablette': 'Tablet',
    'Mobile': 'Mobile',
    'Aperçu Google': 'Google preview',
    "Écrivez ou collez d'abord le texte de l'article": 'Write or paste the article text first',
    'Sélectionnez du texte à mettre en code': 'Select some text to turn into code',
    "Article collé et nettoyé — génération de l'extrait, de la méta description et du mot-clé…": 'Article pasted and cleaned — generating excerpt, meta description and keyword…',
    'Extrait, méta description et mot-clé générés': 'Excerpt, meta description and keyword generated',
    'Champs SEO générés (mode simplifié — IA indisponible)': 'SEO fields generated (simple mode — AI unavailable)',
    'Gras (Ctrl+B)': 'Bold (Ctrl+B)',
    'Italique (Ctrl+I)': 'Italic (Ctrl+I)',
    'Souligné': 'Underline',
    'Barré': 'Strikethrough',
    'Paragraphe': 'Paragraph',
    'Titre H2': 'Heading H2',
    'Titre H3': 'Heading H3',
    'Liste à puces': 'Bulleted list',
    'Liste numérotée': 'Numbered list',
    'Citation': 'Quote',
    'Lien': 'Link',
    'Séparateur': 'Divider',
    "Copier l'URL": 'Copy URL',
    "Aperçu de l'article": 'Article preview',
    'Commencez à écrire votre article…': 'Start writing your article…',
    "Commencez à écrire votre article… Utilisez la barre d'outils ci-dessus ou le panneau IA à droite pour générer du contenu.": 'Start writing your article… Use the toolbar above or the AI panel on the right to generate content.',
    'Titre de votre article': 'Your article title',
    'La méta description apparaîtra ici…': 'The meta description will appear here…',
    'IRVE, LOM, load balancing…': 'EVSE, LOM, load balancing…',
    'IRVE': 'EVSE',
    'Nom du salon / événement': 'Trade show / event name',
    'Décembre 2021': 'December 2021',
    'Équipe Sparklin': 'Sparklin team',
    'Texte affiché': 'Displayed text',
    '🤝 Contacts qualifiés 🔌 Démo live des bornes': '🤝 Qualified leads 🔌 Live charger demo',
    'Début': 'Start', 'Court': 'Short', 'Moyen': 'Medium', 'Long': 'Long', 'Complet': 'Full',
    'Excellent': 'Excellent', 'À améliorer': 'Needs work',
    'Slug défini': 'Slug set',
    'Mot-clé dans le titre': 'Keyword in title',
    'Mot-clé dans le corps': 'Keyword in body',
    'Aucune version sauvegardée': 'No saved versions',
    'Version restaurée': 'Version restored',
    'URL copiée': 'URL copied',
    'Sauver': 'Save',
    "Supprimer l'image": 'Remove image',
    'Fichier image requis': 'Image file required',
    '(Sans titre)': '(Untitled)',
    'APERÇU — non publié': 'PREVIEW — not published',
    'Par': 'By',
    "Vous éditez le texte source en français. Utilisez Aperçu pour lire la traduction anglaise de l'article.": 'You are editing the French source text. Use Preview to read the English translation of the article.',
    'Révision': 'Review',
    'Titre requis': 'Title required',
    "Le titre est requis": 'Title is required',
    'Choisissez une date de publication': 'Choose a publication date',
    'Brouillon sauvegardé': 'Draft saved',
    'Article publié — en ligne sur sparklin.io dans 1 à 2 min': 'Article published — live on sparklin.io in 1–2 min',
    'Mode focus activé — Appuyez Échap pour quitter': 'Focus mode on — press Esc to exit',

    /* ── AI ── */
    'Configurée — prête à générer du contenu': 'Configured — ready to generate content',
    'Non configurée — définissez ANTHROPIC_API_KEY dans les variables Netlify': 'Not configured — set ANTHROPIC_API_KEY in the Netlify variables',
    'Analyse du titre et des mots-clés…': 'Analysing the title and keywords…',
    'Structuration du plan éditorial…': 'Structuring the editorial outline…',
    'Rédaction du contenu principal…': 'Writing the main content…',
    'Optimisation SEO et lisibilité…': 'Optimising SEO and readability…',
    'Relecture et finalisation…': 'Proofreading and finalising…',
    'Nouveau mot-clé :': 'New keyword:',
    'Ajoutez un titre pour générer': 'Add a title to generate',
    'Erreur pendant la génération': 'Error during generation',
    'Contenu généré avec succès !': 'Content generated successfully!',
    'La génération prend plus de temps que prévu — réessayez dans une minute': 'Generation is taking longer than expected — try again in a minute',
    'Erreur inattendue pendant la génération': 'Unexpected error during generation',
    'Génération…': 'Generating…',
    'Générer': 'Generate',
    'Regénérer': 'Regenerate',
    'API IA (Anthropic)': 'AI API (Anthropic)',

    /* ── articles list / dashboard ── */
    'Articles supprimés': 'Articles deleted',
    'Article dupliqué en brouillon': 'Article duplicated as draft',
    'Article supprimé': 'Article deleted',
    'Articles récents': 'Recent articles',
    'Brouillons': 'Drafts', 'Publiés': 'Published', 'Planifiés': 'Scheduled',
    'Tous →': 'All →',
    'Activité': 'Activity',
    'Analytiques': 'Analytics',
    'Contenu': 'Content',
    'Rédaction': 'Writing',
    'Nouveau': 'New',
    'Objectif mots': 'Word goal',
    'Langues': 'Languages',
    'Aucun article dans cette langue': 'No articles in this language',
    'Aucun événement.': 'No events.',
    'Supprimer cet événement ?': 'Delete this event?',
    'Supprimer ce lien presse ?': 'Delete this press link?',

    /* ── events / press ── */
    'Événement mis à jour': 'Event updated',
    'Événement créé': 'Event created',
    'Statut mis à jour': 'Status updated',
    'Événement supprimé': 'Event deleted',
    'Échec de la suppression': 'Deletion failed',
    'Titre, média et URL sont requis': 'Title, outlet and URL are required',
    'Ajout en cours…': 'Adding…',
    'Lien ajouté': 'Link added',
    'Lien supprimé': 'Link deleted',
    'Chargement…': 'Loading…',
    'Session expirée, reconnectez-vous': 'Session expired, please sign in again',
    'Session expirée': 'Session expired',
    'Connexion interrompue (requête trop longue ou réseau coupé)': 'Connection interrupted (request too long or network lost)',
    'Erreur': 'Error',

    /* ── analytics ── */
    'Taux de rebond': 'Bounce rate',
    'Temps moyen': 'Avg. time',
    'Visiteurs uniques': 'Unique visitors',
    'Vues totales': 'Total views',
    'Vues par jour · 30 jours': 'Views per day · 30 days',
    '30 derniers jours — données de démonstration, en cours de connexion': 'Last 30 days — demo data, connection in progress',
    '+2 mois': '+2 months',
    '1 prévu': '1 scheduled',
    '1 avr.': '1 Apr', '30 avr.': '30 Apr',

    /* ── settings ── */
    'Configuration du blog Sparklin': 'Sparklin blog configuration',
    'Définissez les marchés géographiques actifs pour le blog Sparklin.': 'Define the active geographic markets for the Sparklin blog.',
    'Marché principal — France': 'Main market — France',
    'Marché Allemagne · Autriche · Suisse': 'Germany · Austria · Switzerland market',
    'Marché Espagne · Amérique latine': 'Spain · Latin America market',
    'Marché Thaïlande': 'Thailand market',
    'Marché Malaisie': 'Malaysia market',
    'Marché Indonésie': 'Indonesia market',
    'Marché Turquie': 'Turkey market',
    'Marché Vietnam': 'Vietnam market',
    'Marché Philippines': 'Philippines market',
    "Génère une image OG par article": 'Generates an OG image per article',
    'Connecté · UA-XXXXXX': 'Connected · UA-XXXXXX',
    'Actif · 3 formulaires': 'Active · 3 forms',
    'Solutions de recharge IRVE connectées pour entreprises, collectivités et hôtels.': 'Connected EV charging solutions for businesses, local authorities and hotels.',
    "Les liens ci-dessous s'affichent dans la section « Ils parlent de nous » en bas de la page": 'The links below appear in the "They talk about us" section at the bottom of the page',

    /* ── article languages ── */
    'Langues de l\'article': 'Article languages',
    'Toutes les langues': 'All languages',
    'Français seul': 'French only'
  });

  var NUM = '[\\d\\s.,\\u202f\\u00a0]+';
  function p(re, fn) { PATTERNS.push([re, fn]); }
  p(new RegExp('^(' + NUM + ') mots? · (\\d+) min$'), function (m) { return m[1] + (m[1].trim() === '1' ? ' word' : ' words') + ' · ' + m[2] + ' min'; });
  p(new RegExp('^(\\S+) · (' + NUM + ') mots$'), function (m) { return m[1] + ' · ' + m[2] + (m[2].trim() === '1' ? ' word' : ' words'); });
  p(/^Contenu ≥ 500 mots \((\d+)\)$/, function (m) { return 'Content ≥ 500 words (' + m[1] + ')'; });
  p(/^Objectif (\d+)%$/, function (m) { return 'Goal ' + m[1] + '%'; });
  p(/^(\d+) articles?$/, function (m) { return m[1] + (m[1] === '1' || m[1] === '0' ? ' article' : ' articles'); });
  p(/^(\d+) liens?$/, function (m) { return m[1] + (m[1] === '1' || m[1] === '0' ? ' link' : ' links'); });
  p(/^(\d+) événements? · (\d+) à venir$/, function (m) { return m[1] + (m[1] === '1' ? ' event' : ' events') + ' · ' + m[2] + ' upcoming'; });
  p(/^Mise à jour : (.+)$/, function (m) { return 'Updated: ' + m[1]; });
  p(/^Planifié pour le (.+)$/, function (m) { return 'Scheduled for ' + m[1]; });
  p(/^Supprimer les (\d+) articles sélectionnés \?$/, function (m) { return 'Delete the ' + m[1] + ' selected articles?'; });
  p(/^Supprimer "(.*)" \?$/, function (m) { return 'Delete "' + m[1] + '"?'; });
  p(/^Titre 50–70 caractères \((\d+)\)$/, function (m) { return 'Title 50–70 characters (' + m[1] + ')'; });
  p(/^Méta 120–160 caractères \((\d+)\)$/, function (m) { return 'Meta 120–160 characters (' + m[1] + ')'; });
  p(/^(\d+) sélectionnés?$/, function (m) { return m[1] + ' selected'; });

  function translate(str) {
    var m = /^(\s*)([\s\S]*?)(\s*)$/.exec(str);
    var core = norm(m[2]);
    if (!core) return null;
    var out = EXACT[core];
    if (out === undefined) {
      for (var i = 0; i < PATTERNS.length; i++) {
        var r = PATTERNS[i][0].exec(core);
        if (r) { out = PATTERNS[i][1](r); break; }
      }
    }
    return out === undefined || out === null ? null : m[1] + out + m[3];
  }

  function skipped(el) { return !!(el && el.closest && el.closest(SKIP)); }

  function processText(node) {
    if (skipped(node.parentNode)) return;
    var rec = textStore.get(node);
    if (lang === 'en') {
      if (rec && node.data === rec.en) return;
      var t = translate(node.data);
      if (t !== null && t !== node.data) { textStore.set(node, { fr: node.data, en: t }); node.data = t; }
    } else if (rec && node.data === rec.en) {
      node.data = rec.fr;
    }
  }

  function processAttr(el, name) {
    if (!el.getAttribute || skipped(el)) return;
    var v = el.getAttribute(name);
    if (v === null) return;
    var recs = attrStore.get(el);
    var rec = recs && recs[name];
    if (lang === 'en') {
      if (rec && v === rec.en) return;
      var t = translate(v);
      if (t !== null && t !== v) {
        if (!recs) { recs = {}; attrStore.set(el, recs); }
        recs[name] = { fr: v, en: t };
        el.setAttribute(name, t);
      }
    } else if (rec && v === rec.en) {
      el.setAttribute(name, rec.fr);
    }
  }

  function walk(root) {
    if (!root) return;
    if (root.nodeType === 3) { processText(root); return; }
    if (root.nodeType !== 1 || skipped(root)) return;
    var tw = document.createTreeWalker(root, 1 | 4, {
      acceptNode: function (n) {
        return n.nodeType === 1 && n.matches(SKIP) ? 2 /* REJECT: sous-arbre exclu */ : 1;
      }
    });
    var n = root;
    do {
      if (n.nodeType === 3) processText(n);
      else for (var i = 0; i < ATTRS.length; i++) processAttr(n, ATTRS[i]);
    } while ((n = tw.nextNode()));
    walkTx(root);
  }

  /* ── Contenu éditorial (titres d'articles, événements, presse…) ──
     Les éléments marqués data-tx contiennent du texte saisi par les rédacteurs
     (en français) : en EN on affiche sa traduction — d'abord depuis le cache /
     les traductions déjà en base (preload), sinon via la fonction admin-translate. */
  var TX = Object.create(null);
  var TX_KEY = 'sk_admin_tx_en';
  try { var saved = JSON.parse(localStorage.getItem(TX_KEY) || '{}'); Object.keys(saved).forEach(function (k) { TX[k] = saved[k]; }); } catch (e) {}
  function persistTx() {
    try {
      var keys = Object.keys(TX);
      var out = {};
      keys.slice(-500).forEach(function (k) { out[k] = TX[k]; });
      localStorage.setItem(TX_KEY, JSON.stringify(out));
    } catch (e) {}
  }
  var txStore = new WeakMap();
  var txPending = Object.create(null);
  var txTimer = null;

  function txApply(el, fr, en) {
    if (!en || en === fr) return;
    txStore.set(el, { fr: fr, en: en });
    el.textContent = en;
  }

  function processTx(el) {
    var rec = txStore.get(el);
    if (lang === 'en') {
      var cur = el.textContent;
      if (rec && cur === rec.en) return;
      var fr = cur.replace(/\s+/g, ' ').trim();
      if (!fr) return;
      if (TX[fr] !== undefined) { txApply(el, fr, TX[fr]); return; }
      (txPending[fr] = txPending[fr] || []).push(el);
      clearTimeout(txTimer);
      txTimer = setTimeout(flushTx, 200);
    } else if (rec && el.textContent === rec.en) {
      el.textContent = rec.fr;
    }
  }

  async function authToken() {
    try {
      var r = await window.__sbAdmin.auth.getSession();
      return r && r.data && r.data.session ? r.data.session.access_token : null;
    } catch (e) { return null; }
  }

  /* promesses à attendre avant d'appeler l'IA (ex. traductions déjà en base en cours de chargement) */
  var holds = [];
  function hold(promise) { holds.push(promise); }

  async function flushTx() {
    if (holds.length) { await Promise.all(holds.map(function (h) { return Promise.resolve(h).catch(function () {}); })); holds = []; }
    var texts = Object.keys(txPending).filter(function (t) { return TX[t] === undefined; });
    if (!texts.length) { txPending = Object.create(null); return; }
    var batch = txPending; txPending = Object.create(null);
    var token = await authToken();
    if (!token) return;
    for (var i = 0; i < texts.length; i += 25) {
      var chunk = texts.slice(i, i + 25);
      try {
        var resp = await fetch('/.netlify/functions/admin-translate', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', Authorization: 'Bearer ' + token },
          body: JSON.stringify({ texts: chunk })
        });
        if (!resp.ok) continue;
        var data = await resp.json();
        chunk.forEach(function (fr, j) {
          var en = data.translations[j];
          TX[fr] = en;
          (batch[fr] || []).forEach(function (el) { if (lang === 'en' && el.textContent.replace(/\s+/g, ' ').trim() === fr) txApply(el, fr, en); });
        });
        persistTx();
      } catch (e) { /* hors ligne / quota : on garde le français */ }
    }
  }

  function walkTx(root) {
    if (!root || root.nodeType !== 1) return;
    if (root.matches('[data-tx]')) processTx(root);
    root.querySelectorAll('[data-tx]').forEach(processTx);
  }

  function preload(pairs) {
    Object.keys(pairs).forEach(function (fr) { if (pairs[fr]) TX[norm(fr)] = pairs[fr]; });
    if (lang === 'en') walkTx(document.body);
  }

  var titleFr = null;
  function setLang(l) {
    lang = l === 'en' ? 'en' : 'fr';
    document.documentElement.lang = lang;
    if (titleFr === null) titleFr = document.title;
    if (lang === 'en') { var tt = translate(titleFr); document.title = tt !== null ? tt : titleFr; } else { document.title = titleFr; }
    walk(document.body);
  }

  var nativeConfirm = window.confirm, nativePrompt = window.prompt, nativeAlert = window.alert;
  function tr(s) { if (lang !== 'en' || typeof s !== 'string') return s; var t = translate(s); return t === null ? s : t; }
  window.confirm = function (m) { return nativeConfirm.call(window, tr(m)); };
  window.prompt = function (m, d) { return nativePrompt.call(window, tr(m), d); };
  window.alert = function (m) { return nativeAlert.call(window, tr(m)); };

  var observer = new MutationObserver(function (muts) {
    if (lang !== 'en') return;
    muts.forEach(function (m) {
      if (m.type === 'characterData') processText(m.target);
      else if (m.type === 'attributes') processAttr(m.target, m.attributeName);
      else m.addedNodes.forEach(walk);
    });
  });

  function start() {
    observer.observe(document.documentElement, { childList: true, subtree: true, characterData: true, attributes: true, attributeFilter: ATTRS });
    var saved = null;
    try { saved = localStorage.getItem('sk_admin_ui_lang'); } catch (e) {}
    setLang(saved === 'en' ? 'en' : 'fr');
  }

  window.skAdminI18n = { setLang: setLang, extend: extend, preload: preload, hold: hold, t: tr, refresh: function () { walk(document.body); } };

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start); else start();
})();
