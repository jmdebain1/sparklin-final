/*
 * SparklinInterconnect v6
 * Schema d'architecture clair, fond blanc, photos produit reelles.
 * Spark Pilot (plateforme) en haut, les 3 bornes du catalogue en bas,
 * tarification et paiement en capacites laterales.
 */
function initSparklinInterconnect() {
  const wrap = document.getElementById('sparklin-interconnect');
  if (!wrap || wrap._init) return;
  wrap._init = true;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* i18n — valeurs injectées par PHP (window.SK_INTERCONNECT_I18N), repli FR */
  const _i18n = window.SK_INTERCONNECT_I18N || {};
  const IT = (k, fr) => (_i18n[k] != null && _i18n[k] !== '') ? _i18n[k] : fr;

  /* ── Palette (design system Sparklin) ── */
  const CO = {
    orange:   '#E8563A',
    dark:     '#1A1A2E',
    mid:      '#4A4A6A',
    light:    '#6B6B8A',
    white:    '#FFFFFF',
    off:      '#F7F6F3',
    border:   '#E8E6E0',
    borderStrong: '#D4D0C8',
    line:     'rgba(26,26,46,0.13)',
    charging: '#2E9E5B',
    available:'#3B6FD4',
    offline:  '#B9B6C4',
  };

  /* ── CSS ── */
  if (!document.getElementById('sk6')) {
    const s = document.createElement('style');
    s.id = 'sk6';
    s.textContent = `
      @keyframes sk6In   { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
      @keyframes sk6Led  { 0%,100%{opacity:1} 50%{opacity:.35} }
      @keyframes sk6Bar  { 0%,100%{opacity:.8} 50%{opacity:1} }
      #sparklin-interconnect { -webkit-overflow-scrolling: touch; }
      #sparklin-interconnect::-webkit-scrollbar { height: 6px; }
      #sparklin-interconnect::-webkit-scrollbar-thumb { background: ${CO.borderStrong}; border-radius: 3px; }
    `;
    document.head.appendChild(s);
  }

  /* ── SVG SETUP ── */
  const ns = 'http://www.w3.org/2000/svg';
  const W = 900, H = 500;

  wrap.innerHTML = '';
  wrap.style.cssText = [
    'background:' + CO.white,
    'border:1px solid ' + CO.border,
    'border-radius:20px',
    'padding:26px 24px 22px',
    'position:relative',
    'overflow-x:auto',
    'overflow-y:hidden',
    'box-shadow:0 12px 40px rgba(26,26,46,0.06)',
  ].join(';');

  /* Conteneur interne : garde le schema lisible sur mobile (scroll horizontal) */
  const inner = document.createElement('div');
  inner.style.cssText = 'min-width:720px;';
  wrap.appendChild(inner);

  /* En-tete */
  const titleDiv = document.createElement('div');
  titleDiv.style.cssText = 'text-align:center;margin-bottom:14px;font-family:"Wix Madefor Display","DM Sans",sans-serif;';
  titleDiv.innerHTML = `
    <div style="font-size:11px;font-weight:700;letter-spacing:.16em;color:${CO.orange};text-transform:uppercase;margin-bottom:5px;">Spark Pilot</div>
    <div style="font-size:12px;color:${CO.light};letter-spacing:.01em;font-weight:300;">${IT('header_sub','Supervision temps réel · Load balancing · Paiement automatisé')}</div>
  `;
  inner.appendChild(titleDiv);

  const svg = document.createElementNS(ns, 'svg');
  svg.setAttribute('viewBox', `0 0 ${W} ${H}`);
  svg.setAttribute('width', '100%');
  svg.style.cssText = 'display:block;overflow:visible;';
  inner.appendChild(svg);

  /* Helpers */
  const mk = (tag, attrs) => {
    const el = document.createElementNS(ns, tag);
    if (attrs) Object.entries(attrs).forEach(([k, v]) => el.setAttribute(k, v));
    return el;
  };
  const G = (a) => mk('g', a);
  const R = (a) => mk('rect', a);
  const C = (a) => mk('circle', a);
  const P = (a) => mk('path', a);
  const T = (a, txt) => { const el = mk('text', a); el.textContent = txt; return el; };
  const FD = '"Wix Madefor Display","DM Sans",sans-serif';
  const FB = '"Wix Madefor Text","DM Sans",sans-serif';

  /* ── DEFS ── */
  const defs = mk('defs');
  defs.innerHTML = `
    <filter id="sk6Card" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="3" stdDeviation="7" flood-color="rgba(26,26,46,0.10)"/>
    </filter>
    <filter id="sk6Soft" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="0" dy="2" stdDeviation="4" flood-color="rgba(26,26,46,0.07)"/>
    </filter>
    <pattern id="sk6Grid" width="36" height="36" patternUnits="userSpaceOnUse">
      <path d="M36 0 H0 V36" fill="none" stroke="rgba(26,26,46,0.035)" stroke-width="1"/>
    </pattern>
  `;
  svg.appendChild(defs);

  /* Fond quadrille tres discret */
  svg.appendChild(R({ x: 0, y: 0, width: W, height: H, fill: 'url(#sk6Grid)' }));

  /* ═══════════════════════════════════════
     LAYOUT
     ═══════════════════════════════════════ */
  /* Plateforme Spark Pilot (haut, centre) */
  const PLX = 290, PLY = 36, PLW = 320, PLH = 126;
  const PL_CX = PLX + PLW / 2, PL_BOTTOM = PLY + PLH;

  /* Cartes laterales */
  const SIDE_Y = 200, SIDE_W = 190, SIDE_H = 100;
  const TAR_CX = 128, PAY_CX = 772;

  /* Cartes produit (bas) — vraies photos */
  const CARD_Y = 320, CARD_W = 186, CARD_H = 150, PHOTO_H = 86;
  const BUS_Y = 250;

  const PRODUCTS = [
    {
      cx: 240,
      img: '/assets/images/prise-connectee-8.jpg',
      alt: 'Spark 1 — prise renforcée connectée 3,7 kW',
      label: 'Spark 1',
      sub: '3,7 kW · Type E/F',
      status: 'charging',
    },
    {
      cx: 450,
      img: '/assets/images/spark-plus-gamme.jpg',
      alt: 'Spark Plus — prise connectée premium',
      label: 'Spark Plus',
      sub: IT('mid', 'MID certifié') + ' · QR',
      status: 'charging',
    },
    {
      cx: 660,
      img: '/assets/images/goe-gamme.jpg',
      alt: 'Sparklin by go-e — borne accélérée 22 kW',
      label: 'Sparklin by go-e',
      sub: '22 kW · Type 2',
      status: 'available',
    },
  ];

  /* ═══════════════════════════════════════
     TRACES ORTHOGONAUX
     ═══════════════════════════════════════ */
  /* Chemin en angles droits avec coins arrondis */
  function ortho(pts, r) {
    r = r || 12;
    let d = `M ${pts[0][0]} ${pts[0][1]}`;
    for (let i = 1; i < pts.length - 1; i++) {
      const [px, py] = pts[i - 1], [cx, cy] = pts[i], [nx, ny] = pts[i + 1];
      const inLen = Math.hypot(cx - px, cy - py);
      const outLen = Math.hypot(nx - cx, ny - cy);
      if (inLen === 0 || outLen === 0) continue;
      const rr = Math.min(r, inLen / 2, outLen / 2);
      const i1x = Math.sign(cx - px), i1y = Math.sign(cy - py);
      const o1x = Math.sign(nx - cx), o1y = Math.sign(ny - cy);
      d += ` L ${cx - i1x * rr} ${cy - i1y * rr}`;
      d += ` Q ${cx} ${cy} ${cx + o1x * rr} ${cy + o1y * rr}`;
    }
    const last = pts[pts.length - 1];
    d += ` L ${last[0]} ${last[1]}`;
    return d;
  }

  const gLines = G();
  svg.appendChild(gLines);

  /* Borne → bus → plateforme */
  const productPaths = PRODUCTS.map(p => {
    const pts = (p.cx === PL_CX)
      ? [[p.cx, CARD_Y], [p.cx, PL_BOTTOM]]
      : [[p.cx, CARD_Y], [p.cx, BUS_Y], [PL_CX, BUS_Y], [PL_CX, PL_BOTTOM]];
    return ortho(pts, 14);
  });

  /* Cartes laterales → plateforme */
  const tarifPath = ortho([[TAR_CX, SIDE_Y], [TAR_CX, 130], [PLX, 130]], 14);
  const payPath   = ortho([[PAY_CX, SIDE_Y], [PAY_CX, 130], [PLX + PLW, 130]], 14);

  [...productPaths, tarifPath, payPath].forEach(d => {
    gLines.appendChild(P({
      d, fill: 'none', stroke: CO.line, 'stroke-width': '1.5', 'stroke-linecap': 'round',
    }));
  });

  /* ═══════════════════════════════════════
     PAQUETS DATA (flux borne → plateforme)
     ═══════════════════════════════════════ */
  const gPkts = G();
  svg.appendChild(gPkts);

  function spawnPacket(pathD, color, dur) {
    if (reduced) return;
    const g = G();
    /* halo blanc pour rester lisible au croisement des traces */
    g.appendChild(C({ r: '5.5', fill: CO.white }));
    g.appendChild(C({ r: '3', fill: color }));
    g.setAttribute('opacity', '0');

    const am = mk('animateMotion');
    am.setAttribute('dur', `${dur}ms`);
    am.setAttribute('repeatCount', '1');
    am.setAttribute('path', pathD);
    am.setAttribute('calcMode', 'spline');
    am.setAttribute('keyTimes', '0;1');
    am.setAttribute('keySplines', '.4 0 .2 1');
    const ao = mk('animate', {
      attributeName: 'opacity', values: '0;1;1;0',
      keyTimes: '0;.1;.85;1', dur: `${dur}ms`, repeatCount: '1',
    });
    g.appendChild(am); g.appendChild(ao);
    gPkts.appendChild(g);
    setTimeout(() => { try { gPkts.removeChild(g); } catch (_) {} }, dur + 80);
  }

  if (!reduced) {
    let pi = 0;
    setInterval(() => {
      const p = PRODUCTS[pi];
      spawnPacket(productPaths[pi], p.status === 'charging' ? CO.charging : CO.available, 1500);
      pi = (pi + 1) % PRODUCTS.length;
    }, 760);
    setInterval(() => spawnPacket(tarifPath, CO.orange, 1400), 3100);
    setInterval(() => spawnPacket(payPath, CO.orange, 1400), 3900);
    setTimeout(() => spawnPacket(productPaths[0], CO.charging, 1500), 250);
    setTimeout(() => spawnPacket(tarifPath, CO.orange, 1400), 700);
  }

  /* ═══════════════════════════════════════
     PLATEFORME — SPARK PILOT
     ═══════════════════════════════════════ */
  const gPilot = G();
  if (!reduced) gPilot.style.cssText = 'animation:sk6In .6s .1s ease both;opacity:0;';
  svg.appendChild(gPilot);

  /* Carte */
  gPilot.appendChild(R({
    x: PLX, y: PLY, width: PLW, height: PLH, rx: '16',
    fill: CO.white, stroke: CO.border, 'stroke-width': '1.5', filter: 'url(#sk6Card)',
  }));
  /* Bandeau d'en-tete */
  gPilot.appendChild(P({
    d: `M ${PLX} ${PLY + 16} A 16 16 0 0 1 ${PLX + 16} ${PLY} L ${PLX + PLW - 16} ${PLY}
        A 16 16 0 0 1 ${PLX + PLW} ${PLY + 16} L ${PLX + PLW} ${PLY + 38} L ${PLX} ${PLY + 38} Z`,
    fill: CO.off,
  }));
  gPilot.appendChild(P({
    d: `M ${PLX} ${PLY + 38} L ${PLX + PLW} ${PLY + 38}`,
    stroke: CO.border, 'stroke-width': '1', fill: 'none',
  }));

  /* Pastille orange + titre */
  gPilot.appendChild(R({ x: PLX + 16, y: PLY + 12, width: 3, height: 14, rx: '1.5', fill: CO.orange }));
  gPilot.appendChild(T({
    x: PLX + 28, y: PLY + 24, 'font-size': '11', 'font-weight': '700',
    'font-family': FD, fill: CO.dark, 'letter-spacing': '0.02em',
  }, 'Spark Pilot'));

  /* Statut en ligne */
  const ledOK = C({ cx: PLX + PLW - 62, cy: PLY + 19, r: '3.5', fill: CO.charging });
  if (!reduced) ledOK.style.animation = 'sk6Led 2.2s ease-in-out infinite';
  gPilot.appendChild(ledOK);
  gPilot.appendChild(T({
    x: PLX + PLW - 52, y: PLY + 23, 'font-size': '9',
    'font-family': FB, fill: CO.light,
  }, 'En ligne'));

  /* Corps : mini graphe de consommation */
  gPilot.appendChild(T({
    x: PLX + 16, y: PLY + 58, 'font-size': '8.5', 'font-weight': '500',
    'font-family': FB, fill: CO.light, 'letter-spacing': '0.04em',
  }, IT('dashboard', 'Dashboard')));

  const BAR_BOTTOM = PLY + PLH - 16;
  const BAR_MAX_H = 52;
  const BAR_W = 26, BAR_GAP = 12, BAR_N = 6;
  const BAR_X0 = PLX + (PLW - (BAR_N * BAR_W + (BAR_N - 1) * BAR_GAP)) / 2;
  const barEls = [];
  [22, 38, 28, 46, 18, 34].forEach((bh, i) => {
    const bx = BAR_X0 + i * (BAR_W + BAR_GAP);
    gPilot.appendChild(R({
      x: bx, y: BAR_BOTTOM - BAR_MAX_H, width: BAR_W, height: BAR_MAX_H, rx: '4',
      fill: CO.off,
    }));
    const bar = R({
      x: bx, y: BAR_BOTTOM - bh, width: BAR_W, height: bh, rx: '4',
      fill: CO.orange, opacity: (0.45 + i * 0.09).toFixed(2),
    });
    if (!reduced) bar.style.animation = `sk6Bar ${1.6 + i * 0.2}s ease-in-out ${i * 0.12}s infinite`;
    gPilot.appendChild(bar);
    barEls.push(bar);
  });

  if (!reduced) {
    const sets = [[22, 38, 28, 46, 18, 34], [34, 22, 44, 30, 38, 20], [18, 46, 24, 38, 28, 42], [42, 26, 48, 18, 34, 30]];
    let si = 0;
    setInterval(() => {
      si = (si + 1) % sets.length;
      sets[si].forEach((h, i) => {
        barEls[i].setAttribute('y', BAR_BOTTOM - h);
        barEls[i].setAttribute('height', h);
      });
    }, 1100);
  }

  /* ═══════════════════════════════════════
     CARTES LATERALES
     ═══════════════════════════════════════ */
  function sideCard(cx, delay) {
    const g = G();
    if (!reduced) g.style.cssText = `animation:sk6In .55s ${delay}s ease both;opacity:0;`;
    g.appendChild(R({
      x: cx - SIDE_W / 2, y: SIDE_Y, width: SIDE_W, height: SIDE_H, rx: '14',
      fill: CO.white, stroke: CO.border, 'stroke-width': '1.5', filter: 'url(#sk6Soft)',
    }));
    svg.appendChild(g);
    return g;
  }

  /* Tarification flexible */
  const gTarif = sideCard(TAR_CX, 0.3);
  gTarif.appendChild(T({
    x: TAR_CX, y: SIDE_Y + 24, 'text-anchor': 'middle', 'font-size': '9.5', 'font-weight': '500',
    'font-family': FB, fill: CO.light, 'letter-spacing': '0.04em',
  }, IT('flexprice', 'Tarification flexible')));
  gTarif.appendChild(T({
    x: TAR_CX, y: SIDE_Y + 58, 'text-anchor': 'middle', 'font-size': '21', 'font-weight': '800',
    'font-family': FD, fill: CO.dark, 'letter-spacing': '-0.02em',
  }, '0,24 €/kWh'));
  gTarif.appendChild(T({
    x: TAR_CX, y: SIDE_Y + 78, 'text-anchor': 'middle', 'font-size': '8.5',
    'font-family': FB, fill: CO.light,
  }, IT('flexprice_sub', 'défini dans Spark Pilot')));

  /* Paiement automatise */
  const gPay = sideCard(PAY_CX, 0.4);
  gPay.appendChild(T({
    x: PAY_CX, y: SIDE_Y + 24, 'text-anchor': 'middle', 'font-size': '9.5', 'font-weight': '500',
    'font-family': FB, fill: CO.light, 'letter-spacing': '0.04em',
  }, IT('autopay', 'Paiement automatisé')));

  const cbW = 112, cbH = 34, cbX = PAY_CX - cbW / 2, cbY = SIDE_Y + 34;
  gPay.appendChild(R({
    x: cbX, y: cbY, width: cbW, height: cbH, rx: '6',
    fill: CO.off, stroke: CO.border, 'stroke-width': '1',
  }));
  gPay.appendChild(R({ x: cbX + 10, y: cbY + 9, width: 16, height: 12, rx: '2', fill: 'rgba(232,86,58,0.30)' }));
  gPay.appendChild(T({
    x: cbX + 34, y: cbY + 22, 'font-size': '10', 'font-weight': '600',
    'font-family': 'ui-monospace,SFMono-Regular,Menlo,monospace', fill: CO.mid,
  }, '•••• 4242'));
  gPay.appendChild(T({
    x: PAY_CX, y: SIDE_Y + 86, 'text-anchor': 'middle', 'font-size': '8.5',
    'font-family': FB, fill: CO.light,
  }, 'CB · Apple Pay · Google Pay'));

  /* ═══════════════════════════════════════
     CARTES PRODUIT — PHOTOS REELLES
     ═══════════════════════════════════════ */
  PRODUCTS.forEach((p, i) => {
    const x = p.cx - CARD_W / 2;
    const g = G();
    if (!reduced) g.style.cssText = `animation:sk6In .55s ${0.5 + i * 0.1}s ease both;opacity:0;`;

    /* Carte */
    g.appendChild(R({
      x, y: CARD_Y, width: CARD_W, height: CARD_H, rx: '14',
      fill: CO.white, stroke: CO.border, 'stroke-width': '1.5', filter: 'url(#sk6Card)',
    }));

    /* Zone photo : coins arrondis en haut uniquement */
    const clipId = `sk6Ph${i}`;
    const clip = mk('clipPath', { id: clipId });
    clip.appendChild(P({
      d: `M ${x} ${CARD_Y + 14} A 14 14 0 0 1 ${x + 14} ${CARD_Y}
          L ${x + CARD_W - 14} ${CARD_Y} A 14 14 0 0 1 ${x + CARD_W} ${CARD_Y + 14}
          L ${x + CARD_W} ${CARD_Y + PHOTO_H} L ${x} ${CARD_Y + PHOTO_H} Z`,
    }));
    defs.appendChild(clip);

    g.appendChild(R({
      x, y: CARD_Y, width: CARD_W, height: PHOTO_H,
      fill: CO.off, 'clip-path': `url(#${clipId})`,
    }));
    const img = mk('image', {
      x, y: CARD_Y, width: CARD_W, height: PHOTO_H,
      preserveAspectRatio: 'xMidYMid slice',
      'clip-path': `url(#${clipId})`,
    });
    img.setAttribute('href', p.img);
    img.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', p.img);
    const desc = mk('title'); desc.textContent = p.alt;
    img.appendChild(desc);
    g.appendChild(img);

    /* Filet de separation photo / texte */
    g.appendChild(P({
      d: `M ${x} ${CARD_Y + PHOTO_H} L ${x + CARD_W} ${CARD_Y + PHOTO_H}`,
      stroke: CO.border, 'stroke-width': '1', fill: 'none',
    }));

    /* Texte */
    g.appendChild(T({
      x: x + 16, y: CARD_Y + PHOTO_H + 22, 'font-size': '13', 'font-weight': '700',
      'font-family': FD, fill: CO.dark, 'letter-spacing': '-0.01em',
    }, p.label));
    g.appendChild(T({
      x: x + 16, y: CARD_Y + PHOTO_H + 37, 'font-size': '9.5',
      'font-family': FB, fill: CO.light,
    }, p.sub));

    /* Statut */
    const stColor = p.status === 'charging' ? CO.charging : CO.available;
    const stLabel = p.status === 'charging' ? IT('charging', 'En charge') : IT('available', 'Disponible');
    const sy = CARD_Y + PHOTO_H + 54;
    const dot = C({ cx: x + 19, cy: sy - 3.5, r: '3.5', fill: stColor });
    if (!reduced) dot.style.animation = `sk6Led ${2 + i * 0.3}s ease-in-out ${i * 0.25}s infinite`;
    g.appendChild(dot);
    g.appendChild(T({
      x: x + 29, y: sy, 'font-size': '9.5', 'font-weight': '500',
      'font-family': FB, fill: stColor,
    }, stLabel));

    svg.appendChild(g);
  });

  /* ═══════════════════════════════════════
     LOAD BALANCING
     ═══════════════════════════════════════ */
  const lbDiv = document.createElement('div');
  lbDiv.style.cssText = 'margin-top:18px;padding:0 2px;';
  lbDiv.innerHTML = `
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;font-family:${FB};">
      <span style="font-size:10.5px;color:${CO.light};letter-spacing:.04em;">Load balancing</span>
      <span id="sk6lbkw" style="font-size:10.5px;color:${CO.dark};font-weight:700;font-variant-numeric:tabular-nums;">55 kW / 100 kW</span>
    </div>
    <div style="width:100%;height:7px;background:${CO.off};border:1px solid ${CO.border};border-radius:4px;overflow:hidden;">
      <div id="sk6lbbar" style="width:55%;height:100%;background:linear-gradient(90deg,${CO.charging} 0%,${CO.orange} 70%,#DC2626 100%);border-radius:4px;transition:width .9s ease-out;"></div>
    </div>
  `;
  inner.appendChild(lbDiv);

  if (!reduced) {
    const vals = [38, 55, 70, 46, 62, 80, 44, 58];
    let vi = 0;
    setInterval(() => {
      vi = (vi + 1) % vals.length;
      const v = vals[vi];
      const bar = document.getElementById('sk6lbbar');
      const kw = document.getElementById('sk6lbkw');
      if (bar) bar.style.width = v + '%';
      if (kw) kw.textContent = v + ' kW / 100 kW';
    }, 2300);
  }

  /* ═══════════════════════════════════════
     LEGENDE
     ═══════════════════════════════════════ */
  const legDiv = document.createElement('div');
  legDiv.style.cssText = `display:flex;gap:22px;justify-content:center;flex-wrap:wrap;margin-top:18px;padding-top:16px;border-top:1px solid ${CO.border};font-family:${FB};font-size:11px;color:${CO.light};`;
  const dot = (c) => `<span style="width:8px;height:8px;border-radius:50%;background:${c};display:inline-block;flex-shrink:0;"></span>`;
  legDiv.innerHTML = `
    <span style="display:flex;align-items:center;gap:7px;">${dot(CO.charging)}${IT('charging', 'En charge')}</span>
    <span style="display:flex;align-items:center;gap:7px;">${dot(CO.available)}${IT('available', 'Disponible')}</span>
    <span style="display:flex;align-items:center;gap:7px;">${dot(CO.orange)}${IT('signal', 'Signal Spark Pilot')}</span>
    <span style="display:flex;align-items:center;gap:7px;">${dot(CO.offline)}${IT('offline', 'Hors ligne')}</span>
  `;
  inner.appendChild(legDiv);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('sparklin-interconnect')) setTimeout(initSparklinInterconnect, 200);
  });
} else {
  if (document.getElementById('sparklin-interconnect')) setTimeout(initSparklinInterconnect, 200);
}
