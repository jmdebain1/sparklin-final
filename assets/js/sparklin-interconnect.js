/*
 * SparklinInterconnect v5
 * Bâtiment centré, lignes visibles, Spark Pilot soigné, Paiement lisible
 */
function initSparklinInterconnect() {
  const wrap = document.getElementById('sparklin-interconnect');
  if (!wrap || wrap._init) return;
  wrap._init = true;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* i18n — valeurs injectées par PHP (window.SK_INTERCONNECT_I18N), repli FR */
  const _i18n = window.SK_INTERCONNECT_I18N || {};
  const L = (k, fr) => (_i18n[k] != null && _i18n[k] !== '') ? _i18n[k] : fr;

  /* ── CSS ── */
  if (!document.getElementById('sk5')) {
    const s = document.createElement('style');
    s.id = 'sk5';
    s.textContent = `
      @keyframes sk5Float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-5px)} }
      @keyframes sk5FloatS{ 0%,100%{transform:translateY(0)} 50%{transform:translateY(-3px)} }
      @keyframes sk5In    { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:none} }
      @keyframes sk5Led   { 0%,100%{opacity:1} 50%{opacity:.3} }
      @keyframes sk5Win   { 0%,100%{opacity:.5} 40%{opacity:.95} }
      @keyframes sk5Pulse { 0%{transform:scale(1);opacity:.5} 80%{transform:scale(2.4);opacity:0} 100%{transform:scale(2.4);opacity:0} }
      @keyframes sk5Bar   { 0%,100%{opacity:.65} 50%{opacity:1} }
    `;
    document.head.appendChild(s);
  }

  /* ── SVG SETUP ── */
  const ns = 'http://www.w3.org/2000/svg';
  const W = 900, H = 500;

  wrap.innerHTML = '';
  wrap.style.cssText = [
    'background:linear-gradient(160deg,#14142a 0%,#0a0a16 100%)',
    'border-radius:20px',
    'padding:28px 20px 20px',
    'position:relative',
    'overflow:hidden',
  ].join(';');

  /* Title */
  const titleDiv = document.createElement('div');
  titleDiv.style.cssText = 'text-align:center;margin-bottom:16px;font-family:"Wix Madefor Display","DM Sans",sans-serif;';
  titleDiv.innerHTML = `
    <div style="font-size:12px;font-weight:700;letter-spacing:.2em;color:#E8563A;text-transform:uppercase;margin-bottom:4px;">Spark Pilot</div>
    <div style="font-size:11px;color:rgba(255,255,255,.38);letter-spacing:.02em;">Supervision temps réel · Load balancing · Paiement automatisé</div>
  `;
  wrap.appendChild(titleDiv);

  const svg = document.createElementNS(ns, 'svg');
  svg.setAttribute('viewBox', `0 0 ${W} ${H}`);
  svg.setAttribute('width', '100%');
  svg.style.cssText = 'display:block;overflow:visible;';
  wrap.appendChild(svg);

  /* ── DEFS ── */
  const defs = document.createElementNS(ns, 'defs');
  defs.innerHTML = `
    <filter id="sk5O" x="-80%" y="-80%" width="260%" height="260%">
      <feGaussianBlur stdDeviation="5" result="b"/>
      <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
    </filter>
    <filter id="sk5G" x="-80%" y="-80%" width="260%" height="260%">
      <feGaussianBlur stdDeviation="3.5" result="b"/>
      <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
    </filter>
    <filter id="sk5B" x="-80%" y="-80%" width="260%" height="260%">
      <feGaussianBlur stdDeviation="3" result="b"/>
      <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
    </filter>
    <filter id="sk5Blur" x="-40%" y="-40%" width="180%" height="180%">
      <feGaussianBlur stdDeviation="3.5"/>
    </filter>
    <filter id="sk5Sh" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="4" stdDeviation="10" flood-color="rgba(0,0,0,.55)"/>
    </filter>
    <filter id="sk5ShC" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="2" stdDeviation="6" flood-color="rgba(232,86,58,.18)"/>
    </filter>
    <radialGradient id="sk5AtmO" cx="22%" cy="65%" r="45%">
      <stop offset="0%" stop-color="#E8563A" stop-opacity=".14"/>
      <stop offset="100%" stop-color="#E8563A" stop-opacity="0"/>
    </radialGradient>
    <radialGradient id="sk5AtmB" cx="80%" cy="30%" r="40%">
      <stop offset="0%" stop-color="#3B82F6" stop-opacity=".09"/>
      <stop offset="100%" stop-color="#3B82F6" stop-opacity="0"/>
    </radialGradient>
  `;
  svg.appendChild(defs);

  /* Helpers */
  const mk = (tag, attrs) => {
    const el = document.createElementNS(ns, tag);
    if (attrs) Object.entries(attrs).forEach(([k,v]) => el.setAttribute(k, v));
    return el;
  };
  const G = (a) => mk('g', a);
  const R = (a) => mk('rect', a);
  const C = (a) => mk('circle', a);
  const T = (a, txt) => { const el = mk('text', a); el.textContent = txt; return el; };
  const P = (a) => mk('path', a);
  const L = (a) => mk('line', a);
  const anim = (el, css) => { el.style.cssText = (el.style.cssText||'') + css; return el; };

  /* Atmosphere */
  svg.appendChild(R({x:0,y:0,width:W,height:H,fill:'url(#sk5AtmO)'}));
  svg.appendChild(R({x:0,y:0,width:W,height:H,fill:'url(#sk5AtmB)'}));

  /* ═══════════════════════════════════════
     LAYOUT
     ═══════════════════════════════════════ */
  // Bâtiment — centré exactement
  const BX=450, BY=90, BW=250, BH=210;
  const BCENTER_X = BX, BBOTTOM = BY+BH;  // 300

  // Bornes — 4, réparties symétriquement sous le bâtiment
  const BY_BORNE = 390;
  const BORNES = [
    { x:85,  y:BY_BORNE, label:'Spark 1',      sub:'3,7 kW',     status:'charging',  color:'#22C55E', type:'s1'   },
    { x:270, y:BY_BORNE, label:'Spark Plus',   sub:'MID · QR',   status:'charging',  color:'#22C55E', type:'plus' },
    { x:450, y:BY_BORNE, label:'Spark x go-e', sub:'22 kW · T2', status:'available', color:'#3B82F6', type:'goe'  },
    { x:630, y:BY_BORNE, label:'Spark 1',      sub:L('available','Disponible'), status:'available', color:'#3B82F6', type:'s1'   },
  ];

  // Spark Pilot — haut droite, bien espacé
  const PX=762, PY=50, PW=170, PH=130;
  // Tarification — gauche milieu
  const TX=112, TY=210, TW=170, TH=100;
  // Paiement automatisé — droite milieu
  const PAX=760, PAY=250, PAW=164, PAH=100;

  /* ═══════════════════════════════════════
     CONNEXIONS
     ═══════════════════════════════════════ */
  const gLines = G();
  svg.appendChild(gLines);

  /* Lignes bornes → bas du bâtiment */
  /* Elles remontent vers un point d'entrée au bas du bâtiment (centre bas) */
  const batEntryX = BCENTER_X;
  const batEntryY = BBOTTOM;

  BORNES.forEach(b => {
    /* Ligne principale borne → bâtiment */
    const isCharging = b.status === 'charging';
    const lineCol = isCharging ? 'rgba(34,197,94,0.35)' : 'rgba(59,130,246,0.28)';
    const glowCol = isCharging ? 'rgba(34,197,94,0.08)' : 'rgba(59,130,246,0.06)';

    /* Tracé courbé (bezier) via path pour plus de fluidité */
    const midX = (b.x + batEntryX) / 2;
    const midY = b.y - 30;
    const cp1x = b.x, cp1y = midY;
    const cp2x = batEntryX, cp2y = batEntryY + 20;

    /* Ligne de glow élargie derrière */
    gLines.appendChild(P({
      d:`M ${b.x} ${b.y-34} C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${batEntryX} ${batEntryY}`,
      fill:'none', stroke:glowCol, 'stroke-width':'6', 'stroke-linecap':'round'
    }));
    /* Ligne principale */
    gLines.appendChild(P({
      d:`M ${b.x} ${b.y-34} C ${cp1x} ${cp1y}, ${cp2x} ${cp2y}, ${batEntryX} ${batEntryY}`,
      fill:'none', stroke:lineCol, 'stroke-width':'1.8',
      'stroke-dasharray':'4 5', 'stroke-linecap':'round'
    }));
  });

  /* Ligne bâtiment → Spark Pilot */
  const batRightX = BX + BW/2, batMidY = BY + BH*0.35;
  gLines.appendChild(P({
    d:`M ${batRightX} ${batMidY} C ${batRightX+40} ${batMidY}, ${PX-PW/2-30} ${PY+PH/2}, ${PX-PW/2} ${PY+PH/2}`,
    fill:'none', stroke:'rgba(232,86,58,0.5)', 'stroke-width':'2',
    'stroke-dasharray':'5 4', 'stroke-linecap':'round'
  }));
  /* Glow derrière */
  gLines.appendChild(P({
    d:`M ${batRightX} ${batMidY} C ${batRightX+40} ${batMidY}, ${PX-PW/2-30} ${PY+PH/2}, ${PX-PW/2} ${PY+PH/2}`,
    fill:'none', stroke:'rgba(232,86,58,0.12)', 'stroke-width':'8', 'stroke-linecap':'round'
  }));

  /* ═══════════════════════════════════════
     PAQUETS DATA animés
     ═══════════════════════════════════════ */
  const gPkts = G();
  svg.appendChild(gPkts);

  function spawnBornePulse(bi) {
    if (reduced) return;
    const b = BORNES[bi !== undefined ? bi : Math.floor(Math.random()*BORNES.length)];
    const isCharging = b.status === 'charging';
    const col = isCharging ? '#22C55E' : '#3B82F6';
    const filt = isCharging ? 'url(#sk5G)' : 'url(#sk5B)';
    const dur = 950 + Math.random()*500;
    const cp1x=b.x, cp1y=b.y-30, cp2x=BCENTER_X, cp2y=BBOTTOM+20;

    const dot = mk('circle', {r:'3.5', fill:col, filter:filt, opacity:'0'});
    const am = mk('animateMotion');
    am.setAttribute('dur', `${dur}ms`); am.setAttribute('repeatCount','1');
    am.setAttribute('path', `M ${b.x} ${b.y-34} C ${cp1x} ${cp1y} ${cp2x} ${cp2y} ${BCENTER_X} ${BBOTTOM}`);
    am.setAttribute('calcMode','spline'); am.setAttribute('keyTimes','0;1'); am.setAttribute('keySplines','.4 0 .2 1');
    const ao = mk('animate', {attributeName:'opacity', values:'0;.95;.95;0', keyTimes:'0;.08;.88;1', dur:`${dur}ms`, repeatCount:'1'});
    dot.appendChild(am); dot.appendChild(ao);
    gPkts.appendChild(dot);
    setTimeout(() => { try { gPkts.removeChild(dot); } catch(_){} }, dur+80);
  }

  function spawnPilotPulse() {
    if (reduced) return;
    const dur = 1100 + Math.random()*400;
    const dot = mk('circle', {r:'4', fill:'#E8563A', filter:'url(#sk5O)', opacity:'0'});
    const am = mk('animateMotion');
    am.setAttribute('dur', `${dur}ms`); am.setAttribute('repeatCount','1');
    am.setAttribute('path', `M ${batRightX} ${batMidY} C ${batRightX+40} ${batMidY} ${PX-PW/2-30} ${PY+PH/2} ${PX-PW/2} ${PY+PH/2}`);
    am.setAttribute('calcMode','spline'); am.setAttribute('keyTimes','0;1'); am.setAttribute('keySplines','.4 0 .2 1');
    const ao = mk('animate', {attributeName:'opacity', values:'0;1;1;0', keyTimes:'0;.08;.88;1', dur:`${dur}ms`, repeatCount:'1'});
    dot.appendChild(am); dot.appendChild(ao);
    gPkts.appendChild(dot);
    setTimeout(() => { try { gPkts.removeChild(dot); } catch(_){} }, dur+80);
  }

  if (!reduced) {
    let bi = 0;
    setInterval(() => { spawnBornePulse(bi); bi=(bi+1)%BORNES.length; }, 520);
    setInterval(spawnPilotPulse, 1700);
    [150, 420, 750, 1050].forEach(t => setTimeout(() => spawnBornePulse(), t));
    setTimeout(spawnPilotPulse, 500);
  }

  /* ═══════════════════════════════════════
     BÂTIMENT (centré)
     ═══════════════════════════════════════ */
  const gBat = G({filter:'url(#sk5Sh)'});
  svg.appendChild(gBat);

  /* Corps */
  gBat.appendChild(R({
    x:BX-BW/2, y:BY, width:BW, height:BH, rx:'18',
    fill:'#1a1a38', stroke:'rgba(232,86,58,0.30)', 'stroke-width':'1.5'
  }));
  /* Toit */
  const roofW = BW + 50;
  gBat.appendChild(P({
    d:`M${BX-roofW/2} ${BY} L${BX} ${BY-62} L${BX+roofW/2} ${BY} Z`,
    fill:'#1a1a38', stroke:'rgba(232,86,58,0.28)', 'stroke-width':'1.5'
  }));

  /* Fenêtres 3×4 */
  const winCols = ['#2952a3','#1e3d7a','#3468b8','#1e3d7a','#3468b8','#2952a3','#1e3d7a','#3468b8','#2952a3','#1e3d7a','#3468b8','#2952a3'];
  let wi = 0;
  for (let row=0; row<3; row++) {
    for (let col=0; col<4; col++) {
      const wx = (BX - BW/2) + 22 + col*55;
      const wy = BY + 20 + row*58;
      const wr = R({x:wx, y:wy, width:40, height:36, rx:'6',
        fill:winCols[wi%winCols.length], stroke:'rgba(59,130,246,0.3)', 'stroke-width':'1'});
      if (!reduced) {
        const d = wi * 0.25;
        wr.style.animation = `sk5Win ${2.0+wi*.18}s ease-in-out ${d*.3}s infinite`;
      }
      gBat.appendChild(wr);
      wi++;
    }
  }

  /* Porte */
  const doorX = BX-18, doorY = BY+BH-58, doorW=36, doorH=50;
  gBat.appendChild(R({x:doorX, y:doorY, width:doorW, height:doorH, rx:'6',
    fill:'#0a0a18', stroke:'rgba(232,86,58,0.25)', 'stroke-width':'1'}));
  gBat.appendChild(C({cx:doorX+doorW-8, cy:doorY+doorH/2, r:'2.5', fill:'#E8563A'}));

  /* Signal pulsant au centre du bâtiment (connexion réseau) */
  const sigX = BX, sigY = BY + BH*0.5;
  if (!reduced) {
    const gSig = G();
    const pulse1 = C({cx:sigX, cy:sigY, r:'6', fill:'none', stroke:'#E8563A', 'stroke-width':'1.5', opacity:'.7'});
    const pulse2 = C({cx:sigX, cy:sigY, r:'6', fill:'none', stroke:'#E8563A', 'stroke-width':'1'});
    pulse1.style.animation = 'sk5Pulse 2.4s ease-out .0s infinite';
    pulse1.style.transformOrigin = `${sigX}px ${sigY}px`;
    pulse2.style.animation = 'sk5Pulse 2.4s ease-out 1.2s infinite';
    pulse2.style.transformOrigin = `${sigX}px ${sigY}px`;
    gSig.appendChild(pulse1); gSig.appendChild(pulse2);
    gSig.appendChild(C({cx:sigX, cy:sigY, r:'4', fill:'#E8563A', filter:'url(#sk5O)'}));
    svg.appendChild(gSig);
  }

  /* ═══════════════════════════════════════
     SPARK PILOT — card bien présentée
     ═══════════════════════════════════════ */
  const gPilot = G();
  if (!reduced) anim(gPilot, 'animation:sk5In .7s .15s ease both;opacity:0;');
  svg.appendChild(gPilot);

  const gPilotFG = G();
  if (!reduced) {
    gPilotFG.style.animation = 'sk5FloatS 4s ease-in-out infinite';
    gPilotFG.style.transformOrigin = `${PX}px ${PY+PH/2}px`;
    gPilotFG.style.transformBox = 'fill-box';
  }

  /* Card fond avec bordure orange subtile */
  gPilotFG.appendChild(R({
    x:PX-PW/2, y:PY, width:PW, height:PH, rx:'16',
    fill:'#181830', stroke:'rgba(232,86,58,0.45)', 'stroke-width':'1.5',
    filter:'url(#sk5ShC)'
  }));
  /* En-tête de la card */
  gPilotFG.appendChild(R({
    x:PX-PW/2, y:PY, width:PW, height:36, rx:'16',
    fill:'rgba(232,86,58,0.12)'
  }));
  /* Fix arrondi bas de l'entête */
  gPilotFG.appendChild(R({
    x:PX-PW/2, y:PY+22, width:PW, height:14,
    fill:'rgba(232,86,58,0.12)'
  }));
  /* Titre dans l'entête */
  gPilotFG.appendChild(T({
    x:PX, y:PY+22, 'text-anchor':'middle', 'font-size':'10', 'font-weight':'700',
    'font-family':'"Wix Madefor Display","DM Sans",sans-serif, letter-spacing:.06em',
    fill:'#E8563A', 'letter-spacing':'0.06em'
  }, 'SPARK PILOT'));

  /* LED verte EN LIGNE */
  const ledOK = C({cx:PX+PW/2-16, cy:PY+18, r:'5', fill:'#22C55E', filter:'url(#sk5G)'});
  if (!reduced) {
    ledOK.style.animation = 'sk5Led 1.9s ease-in-out infinite';
    ledOK.style.transformOrigin = `${PX+PW/2-16}px ${PY+18}px`;
  }
  gPilotFG.appendChild(ledOK);

  /* Écran du dashboard (intérieur) */
  gPilotFG.appendChild(R({
    x:PX-PW/2+12, y:PY+42, width:PW-24, height:PH-58, rx:'10',
    fill:'#0a0a18', stroke:'rgba(232,86,58,0.15)', 'stroke-width':'1'
  }));

  /* Label "kW consommés" dans l'écran */
  gPilotFG.appendChild(T({
    x:PX-PW/2+16, y:PY+56, 'font-size':'7', fill:'rgba(232,86,58,.55)',
    'font-family':'"DM Sans",sans-serif'
  }, 'kW consommés'));

  /* Barres du dashboard (6) */
  const BAR_BOTTOM = PY + PH - 18;
  const BAR_MAX_H  = 42;
  const barHeights = [20, 36, 26, 42, 16, 32];
  const barEls = [];
  barHeights.forEach((bh, i) => {
    const bx2 = PX-PW/2 + 16 + i*22;
    /* Fond de barre (capacité max) */
    gPilotFG.appendChild(R({
      x:bx2, y:BAR_BOTTOM-BAR_MAX_H, width:14, height:BAR_MAX_H, rx:'3',
      fill:'rgba(232,86,58,0.10)'
    }));
    /* Barre active */
    const bar = R({
      x:bx2, y:BAR_BOTTOM-bh, width:14, height:bh, rx:'3',
      fill:'#E8563A', opacity:(0.55+i*0.08).toFixed(2)
    });
    if (!reduced) {
      bar.style.animation = `sk5Bar ${1.4+i*.18}s ease-in-out ${i*.14}s infinite`;
    }
    gPilotFG.appendChild(bar);
    barEls.push({el:bar, base:bh});
  });

  gPilot.appendChild(gPilotFG);

  /* Label sous la card */
  gPilot.appendChild(T({
    x:PX, y:PY+PH+16, 'text-anchor':'middle', 'font-size':'11', 'font-weight':'700',
    'font-family':'"Wix Madefor Display","DM Sans",sans-serif',
    fill:'#E8563A', 'letter-spacing':'0.04em'
  }, 'Spark Pilot'));
  gPilot.appendChild(T({
    x:PX, y:PY+PH+30, 'text-anchor':'middle', 'font-size':'9',
    'font-family':'"DM Sans",sans-serif', fill:'rgba(255,255,255,.38)'
  }, L('dashboard','Dashboard')));

  /* Animation barres */
  if (!reduced) {
    const barSets = [
      [20,36,26,42,16,32], [32,20,40,28,36,18], [16,42,22,36,26,38],
      [38,24,44,16,32,28]
    ];
    let bsi = 0;
    setInterval(() => {
      bsi = (bsi+1) % barSets.length;
      const hs = barSets[bsi];
      barEls.forEach(({el},i) => {
        el.setAttribute('y', BAR_BOTTOM-hs[i]);
        el.setAttribute('height', hs[i]);
      });
    }, 950);
  }

  /* ═══════════════════════════════════════
     TARIFICATION FLEXIBLE (gauche)
     ═══════════════════════════════════════ */
  const gTarif = G();
  if (!reduced) anim(gTarif, 'animation:sk5In .6s .4s ease both;opacity:0;');
  svg.appendChild(gTarif);

  const gTarifFG = G();
  if (!reduced) {
    gTarifFG.style.animation = 'sk5FloatS 4.4s ease-in-out .6s infinite';
    gTarifFG.style.transformOrigin = `${TX}px ${TY+TH/2}px`;
    gTarifFG.style.transformBox = 'fill-box';
  }
  gTarifFG.appendChild(R({
    x:TX-TW/2, y:TY, width:TW, height:TH, rx:'14',
    fill:'#181830', stroke:'rgba(232,86,58,0.28)', 'stroke-width':'1.5',
    filter:'url(#sk5Sh)'
  }));
  gTarifFG.appendChild(T({
    x:TX, y:TY+20, 'text-anchor':'middle', 'font-size':'9.5', 'font-weight':'600',
    'font-family':'"DM Sans",sans-serif', fill:'rgba(255,255,255,.55)'
  }, L('flexprice','Tarification flexible')));
  gTarifFG.appendChild(T({
    x:TX, y:TY+50, 'text-anchor':'middle', 'font-size':'22', 'font-weight':'700',
    'font-family':'"Wix Madefor Display","DM Sans",sans-serif', fill:'#E8563A'
  }, '0,24 €/kWh'));
  gTarifFG.appendChild(T({
    x:TX, y:TY+68, 'text-anchor':'middle', 'font-size':'8',
    'font-family':'"DM Sans",sans-serif', fill:'rgba(255,255,255,.28)'
  }, L('flexprice_sub','défini dans Spark Pilot')));
  gTarif.appendChild(gTarifFG);

  /* Connexion tarif → bâtiment (petite ligne) */
  gLines.appendChild(P({
    d:`M ${TX+TW/2} ${TY+TH/2} C ${TX+TW/2+30} ${TY+TH/2}, ${BX-BW/2-20} ${BY+BH*0.6}, ${BX-BW/2} ${BY+BH*0.55}`,
    fill:'none', stroke:'rgba(232,86,58,0.18)', 'stroke-width':'1.5',
    'stroke-dasharray':'3 5', 'stroke-linecap':'round'
  }));

  /* ═══════════════════════════════════════
     PAIEMENT AUTOMATISÉ (droite)
     ═══════════════════════════════════════ */
  const gPay = G();
  if (!reduced) anim(gPay, 'animation:sk5In .6s .5s ease both;opacity:0;');
  svg.appendChild(gPay);

  const gPayFG = G();
  if (!reduced) {
    gPayFG.style.animation = 'sk5FloatS 4.2s ease-in-out 1s infinite';
    gPayFG.style.transformOrigin = `${PAX}px ${PAY+PAH/2}px`;
    gPayFG.style.transformBox = 'fill-box';
  }

  /* Card fond */
  gPayFG.appendChild(R({
    x:PAX-PAW/2, y:PAY, width:PAW, height:PAH, rx:'14',
    fill:'#181830', stroke:'rgba(232,86,58,0.42)', 'stroke-width':'1.5',
    filter:'url(#sk5ShC)'
  }));
  /* Entête orange doux */
  gPayFG.appendChild(R({
    x:PAX-PAW/2, y:PAY, width:PAW, height:34, rx:'14',
    fill:'rgba(232,86,58,0.14)'
  }));
  gPayFG.appendChild(R({
    x:PAX-PAW/2, y:PAY+20, width:PAW, height:14,
    fill:'rgba(232,86,58,0.14)'
  }));
  /* Titre dans l'entête */
  gPayFG.appendChild(T({
    x:PAX, y:PAY+21, 'text-anchor':'middle', 'font-size':'9.5', 'font-weight':'700',
    'font-family':'"Wix Madefor Display","DM Sans",sans-serif',
    fill:'#E8563A', 'letter-spacing':'0.04em'
  }, L('autopay','Paiement automatisé')));

  /* CB simulée */
  const cbX = PAX-PAW/2+14, cbY = PAY+40, cbW=64, cbH=28;
  gPayFG.appendChild(R({x:cbX, y:cbY, width:cbW, height:cbH, rx:'5',
    fill:'rgba(232,86,58,0.14)', stroke:'rgba(232,86,58,0.40)', 'stroke-width':'1.2'}));
  gPayFG.appendChild(R({x:cbX, y:cbY+7, width:cbW, height:7,
    fill:'rgba(232,86,58,0.30)'}));
  gPayFG.appendChild(T({
    x:cbX+cbW/2, y:cbY+cbH-5, 'text-anchor':'middle', 'font-size':'9',
    'font-family':'monospace', fill:'rgba(255,255,255,0.5)'
  }, '•••• 4242'));

  /* Méthodes de paiement */
  gPayFG.appendChild(T({
    x:PAX, y:PAY+78, 'text-anchor':'middle', 'font-size':'8.5',
    'font-family':'"DM Sans",sans-serif', fill:'rgba(255,255,255,.42)'
  }, 'CB · Apple Pay · Google Pay'));

  gPay.appendChild(gPayFG);

  /* Connexion paiement → bâtiment */
  gLines.appendChild(P({
    d:`M ${PAX-PAW/2} ${PAY+PAH/2} C ${PAX-PAW/2-30} ${PAY+PAH/2}, ${BX+BW/2+20} ${BY+BH*0.7}, ${BX+BW/2} ${BY+BH*0.65}`,
    fill:'none', stroke:'rgba(232,86,58,0.20)', 'stroke-width':'1.5',
    'stroke-dasharray':'3 5', 'stroke-linecap':'round'
  }));

  /* ═══════════════════════════════════════
     BORNES
     ═══════════════════════════════════════ */
  BORNES.forEach((b, i) => {
    const gB = G();
    if (!reduced) anim(gB, `animation:sk5In .55s ${.5+i*.1}s ease both;opacity:0;`);

    const gFG = G();
    if (!reduced) {
      gFG.style.animation = `sk5Float ${3.0+i*.38}s ease-in-out ${i*.55}s infinite`;
      gFG.style.transformOrigin = `${b.x}px ${b.y}px`;
      gFG.style.transformBox = 'fill-box';
    }

    const BW2=84, BH2=100;
    const bx=b.x-BW2/2, by=b.y-BH2/2;

    /* Ombre */
    gFG.appendChild(R({x:bx+2,y:by+5,width:BW2,height:BH2,rx:'13',
      fill:'rgba(0,0,0,.45)',filter:'url(#sk5Blur)'}));
    /* Corps */
    gFG.appendChild(R({x:bx,y:by,width:BW2,height:BH2,rx:'13',
      fill:'#121228', stroke:'rgba(255,255,255,0.09)', 'stroke-width':'1',
      filter:'url(#sk5Sh)'}));

    /* Bandeau haut sombre */
    gFG.appendChild(R({x:bx,y:by,width:BW2,height:BH2*.46,rx:'13',fill:'#0a0a18'}));
    gFG.appendChild(R({x:bx,y:by+BH2*.33,width:BW2,height:BH2*.13,fill:'#0a0a18'}));

    /* Contenu selon type */
    if (b.type === 'plus') {
      /* Écran Spark Plus */
      gFG.appendChild(R({x:bx+8,y:by+8,width:BW2-16,height:30,rx:'6',
        fill:'rgba(232,86,58,0.12)',stroke:'rgba(232,86,58,0.30)','stroke-width':'1'}));
      gFG.appendChild(T({x:b.x,y:by+22,'text-anchor':'middle','font-size':'8.5','font-weight':'600',
        'font-family':'"DM Sans",sans-serif',fill:'#E8563A'},'0,24 €/kWh'));
      gFG.appendChild(T({x:b.x,y:by+32,'text-anchor':'middle','font-size':'6.5',
        'font-family':'"DM Sans",sans-serif',fill:'rgba(255,255,255,.38)'},L('mid','MID certifié')));
    } else if (b.type === 'goe') {
      /* Corps go-e */
      gFG.appendChild(R({x:bx+8,y:by+8,width:BW2-16,height:30,rx:'6',
        fill:'rgba(59,130,246,0.12)',stroke:'rgba(59,130,246,0.25)','stroke-width':'1'}));
      gFG.appendChild(T({x:b.x,y:by+22,'text-anchor':'middle','font-size':'8.5','font-weight':'700',
        'font-family':'"DM Sans",sans-serif',fill:'rgba(255,255,255,.75)'},'go-e'));
      gFG.appendChild(T({x:b.x,y:by+31,'text-anchor':'middle','font-size':'6.5',
        'font-family':'"DM Sans",sans-serif',fill:'rgba(255,255,255,.38)'},'22 kW · T2S'));
      /* Câble */
      gFG.appendChild(P({
        d:`M${b.x} ${by+BH2} Q${b.x+14} ${by+BH2+18} ${b.x+16} ${by+BH2+32}`,
        stroke:'#0a0a18','stroke-width':'7',fill:'none','stroke-linecap':'round'
      }));
      gFG.appendChild(P({
        d:`M${b.x} ${by+BH2} Q${b.x+14} ${by+BH2+18} ${b.x+16} ${by+BH2+32}`,
        stroke:'rgba(80,80,100,.7)','stroke-width':'4',fill:'none','stroke-linecap':'round'
      }));
      gFG.appendChild(R({x:b.x+9,y:by+BH2+30,width:14,height:9,rx:'3',
        fill:'rgba(40,40,60,.9)',stroke:'rgba(255,255,255,.15)','stroke-width':'1'}));
    } else {
      /* Spark 1 : éclair + prise Type E */
      gFG.appendChild(P({
        d:`M${b.x+3} ${by+11} L${b.x-2} ${by+19} L${b.x+1} ${by+19} L${b.x-3} ${by+28}`,
        stroke:'#E8563A','stroke-width':'2','stroke-linecap':'round',fill:'none'
      }));
    }

    /* Prise Type E (s1 et dispo) */
    if (b.type === 's1') {
      const px2=b.x, py2=by+BH2*.72;
      gFG.appendChild(C({cx:px2,cy:py2,r:'10',fill:'rgba(255,255,255,.05)',stroke:'rgba(255,255,255,.12)','stroke-width':'1'}));
      gFG.appendChild(C({cx:px2-4.5,cy:py2-2,r:'2.2',fill:'rgba(255,255,255,.32)'}));
      gFG.appendChild(C({cx:px2+4.5,cy:py2-2,r:'2.2',fill:'rgba(255,255,255,.32)'}));
      gFG.appendChild(R({x:px2-1.5,y:py2+2,width:3,height:5,rx:'1.5',fill:'rgba(255,255,255,.22)'}));
    } else if (b.type !== 'goe') {
      /* go-e : pas de prise visible séparément */
    }

    /* LED + halo */
    const lx=b.x, ly=by+BH2*.84;
    gFG.appendChild(C({cx:lx,cy:ly,r:'8',fill:b.color,opacity:'.15',filter:'url(#sk5Blur)'}));
    const led = C({cx:lx,cy:ly,r:'4',fill:b.color,
      filter:b.color==='#22C55E'?'url(#sk5G)':'url(#sk5B)'});
    if (!reduced) {
      led.style.animation = `sk5Led ${1.6+i*.22}s ease-in-out ${i*.28}s infinite`;
      led.style.transformOrigin = `${lx}px ${ly}px`;
    }
    gFG.appendChild(led);

    gB.appendChild(gFG);

    /* Labels sous borne */
    const lby = by+BH2 + (b.type==='goe' ? 48 : 14);
    gB.appendChild(T({
      x:b.x, y:lby, 'text-anchor':'middle', 'font-size':'11.5', 'font-weight':'700',
      'font-family':'"Wix Madefor Display","DM Sans",sans-serif', fill:'rgba(255,255,255,.85)'
    }, b.label));
    gB.appendChild(T({
      x:b.x, y:lby+14, 'text-anchor':'middle', 'font-size':'9.5', 'font-weight':'600',
      'font-family':'"DM Sans",sans-serif', fill:b.color
    }, b.sub));

    svg.appendChild(gB);
  });

  /* ═══════════════════════════════════════
     LOAD BALANCING BAR
     ═══════════════════════════════════════ */
  const lbDiv = document.createElement('div');
  lbDiv.style.cssText = 'margin-top:12px;padding:0 10px;';
  lbDiv.innerHTML = `
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;font-family:'DM Sans',sans-serif;">
      <span style="font-size:10px;color:rgba(255,255,255,.32);letter-spacing:.04em;">Load balancing</span>
      <span id="sk5lbkw" style="font-size:10px;color:#E8563A;font-weight:700;letter-spacing:.02em;">55 kW / 100 kW</span>
    </div>
    <div style="width:100%;height:7px;background:rgba(255,255,255,.05);border-radius:4px;overflow:hidden;">
      <div id="sk5lbbar" style="width:55%;height:100%;background:linear-gradient(90deg,#22C55E 0%,#E8563A 58%,#DC2626 100%);border-radius:4px;transition:width .9s ease-out;"></div>
    </div>
  `;
  wrap.appendChild(lbDiv);

  if (!reduced) {
    const vals = [38,55,70,46,62,80,44,58];
    let vi=0;
    setInterval(() => {
      vi=(vi+1)%vals.length;
      const v=vals[vi];
      const bar=document.getElementById('sk5lbbar');
      const kw=document.getElementById('sk5lbkw');
      if(bar) bar.style.width=v+'%';
      if(kw)  kw.textContent=v+' kW / 100 kW';
    }, 2300);
  }

  /* ═══════════════════════════════════════
     LÉGENDE
     ═══════════════════════════════════════ */
  const legDiv = document.createElement('div');
  legDiv.style.cssText = 'display:flex;gap:22px;justify-content:center;flex-wrap:wrap;margin-top:12px;font-family:"DM Sans",sans-serif;font-size:11px;color:rgba(255,255,255,.45);';
  legDiv.innerHTML = `
    <span style="display:flex;align-items:center;gap:6px;"><span style="width:9px;height:9px;border-radius:50%;background:#22C55E;display:inline-block;flex-shrink:0;"></span>${L('charging','En charge')}</span>
    <span style="display:flex;align-items:center;gap:6px;"><span style="width:9px;height:9px;border-radius:50%;background:#3B82F6;display:inline-block;flex-shrink:0;"></span>${L('available','Disponible')}</span>
    <span style="display:flex;align-items:center;gap:6px;"><span style="width:9px;height:9px;border-radius:50%;background:#E8563A;display:inline-block;flex-shrink:0;"></span>${L('signal','Signal Spark Pilot')}</span>
    <span style="display:flex;align-items:center;gap:6px;"><span style="width:9px;height:9px;border-radius:50%;background:rgba(255,255,255,.2);display:inline-block;flex-shrink:0;"></span>${L('offline','Hors ligne')}</span>
  `;
  wrap.appendChild(legDiv);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('sparklin-interconnect')) setTimeout(initSparklinInterconnect, 200);
  });
} else {
  if (document.getElementById('sparklin-interconnect')) setTimeout(initSparklinInterconnect, 200);
}
