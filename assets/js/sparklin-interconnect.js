/*
 * SparklinInterconnect v7
 * Schema d'architecture, fond blanc, rendus produit detoures.
 *
 * Deux mises en page reelles, choisies par media query :
 *  - desktop (>720px) : plateforme en haut, 3 bornes en bas, capacites sur les cotes
 *  - mobile  (<=720px) : colonne portrait, plateforme en haut puis rail vertical
 *                        desservant 5 lignes. Aucun scroll horizontal, aucun
 *                        texte reduit par mise a l'echelle.
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

  const FD = '"Wix Madefor Display","DM Sans",sans-serif';
  const FB = '"Wix Madefor Text","DM Sans",sans-serif';

  /* ── CSS ── */
  if (!document.getElementById('sk6')) {
    const s = document.createElement('style');
    s.id = 'sk6';
    s.textContent = `
      @keyframes sk6In   { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:none} }
      @keyframes sk6Led  { 0%,100%{opacity:1} 50%{opacity:.35} }
      @keyframes sk6Bar  { 0%,100%{opacity:.8} 50%{opacity:1} }
      /* flux permanent le long des liaisons (dasharray 7+9=16 → -32 = 2 motifs, boucle sans saut) */
      @keyframes sk6Flow { to { stroke-dashoffset: -32; } }
    `;
    document.head.appendChild(s);
  }

  /* ── Helpers SVG ── */
  const ns = 'http://www.w3.org/2000/svg';
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

  /* Chemin en angles droits, coins arrondis */
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

  /* Image detouree, jamais rognee */
  function cutout(g, href, alt, x, y, w, h) {
    const img = mk('image', {
      x, y, width: w, height: h, preserveAspectRatio: 'xMidYMid meet',
    });
    img.setAttribute('href', href);
    img.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', href);
    const t = mk('title'); t.textContent = alt;
    img.appendChild(t);
    g.appendChild(img);
  }

  /* ── Données ── */
  const PRODUCTS = [
    { img: '/assets/images/spark1-cutout.png',      alt: 'Spark 1 — prise renforcée connectée 3,7 kW',
      label: 'Spark 1',          sub: '3,7 kW · Type E/F',                 status: 'charging'  },
    { img: '/assets/images/spark-plus-cutout.png',  alt: 'Spark Plus — prise connectée premium',
      label: 'Spark Plus',       sub: IT('mid', 'MID certifié') + ' · QR', status: 'charging'  },
    { img: '/assets/images/goe-cutout.png',         alt: 'Sparklin by go-e — borne accélérée 22 kW',
      label: 'Sparklin by go-e', sub: '22 kW · Type 2',                    status: 'available' },
  ];
  const stColor = (p) => p.status === 'charging' ? CO.charging : CO.available;
  const stLabel = (p) => p.status === 'charging'
    ? IT('charging', 'En charge') : IT('available', 'Disponible');

  /* ── Minuteries : remises a zero a chaque reconstruction ── */
  let timers = [];
  const every = (fn, ms) => { timers.push(setInterval(fn, ms)); };
  const later = (fn, ms) => { timers.push(setTimeout(fn, ms)); };
  const clearTimers = () => {
    timers.forEach(t => { clearInterval(t); clearTimeout(t); });
    timers = [];
  };

  /* ═══════════════════════════════════════
     CONSTRUCTION
     ═══════════════════════════════════════ */
  function build(mode) {
    clearTimers();
    const M = (mode === 'mobile');
    const W = M ? 380 : 900;
    const H = M ? 596 : 500;

    wrap.innerHTML = '';
    wrap.style.cssText = [
      'background:' + CO.white,
      'border:1px solid ' + CO.border,
      'border-radius:20px',
      M ? 'padding:18px 14px 16px' : 'padding:26px 24px 22px',
      'position:relative',
      'overflow:hidden',
      'box-shadow:0 12px 40px rgba(26,26,46,0.06)',
    ].join(';');

    /* En-tete */
    const titleDiv = document.createElement('div');
    titleDiv.style.cssText = `text-align:center;margin-bottom:${M ? 12 : 14}px;font-family:${FD};`;
    titleDiv.innerHTML = `
      <div style="font-size:${M ? 10 : 11}px;font-weight:700;letter-spacing:.16em;color:${CO.orange};text-transform:uppercase;margin-bottom:5px;">Spark Pilot</div>
      <div style="font-size:${M ? 11 : 12}px;color:${CO.light};line-height:1.5;font-weight:300;">${IT('header_sub','Supervision temps réel · Load balancing · Paiement automatisé')}</div>
    `;
    wrap.appendChild(titleDiv);

    const svg = mk('svg', { viewBox: `0 0 ${W} ${H}`, width: '100%' });
    svg.style.cssText = 'display:block;overflow:visible;';
    wrap.appendChild(svg);

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
    svg.appendChild(R({ x: 0, y: 0, width: W, height: H, fill: 'url(#sk6Grid)' }));

    const gLines = G(); svg.appendChild(gLines);
    const gPkts  = G(); svg.appendChild(gPkts);

    /* Paquet de donnees circulant vers la plateforme */
    function spawnPacket(pathD, color, dur) {
      if (reduced) return;
      const g = G();
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
      g.appendChild(am);
      g.appendChild(mk('animate', {
        attributeName: 'opacity', values: '0;1;1;0',
        keyTimes: '0;.1;.85;1', dur: `${dur}ms`, repeatCount: '1',
      }));
      gPkts.appendChild(g);
      later(() => { try { gPkts.removeChild(g); } catch (_) {} }, dur + 80);
    }

    /* Liaison : trace de fond + tirets qui defilent */
    function connect(d, color, speed) {
      gLines.appendChild(P({
        d, fill: 'none', stroke: CO.line, 'stroke-width': '1.5', 'stroke-linecap': 'round',
      }));
      const flow = P({
        d, fill: 'none', stroke: color, 'stroke-width': '1.8',
        'stroke-dasharray': '7 9', 'stroke-linecap': 'round', opacity: '0.45',
      });
      if (!reduced) flow.style.animation = `sk6Flow ${speed}s linear infinite`;
      gLines.appendChild(flow);
    }

    /* ── Plateforme Spark Pilot ── */
    const PLX = M ? 14 : 290, PLY = M ? 4 : 36;
    const PLW = M ? 352 : 320, PLH = M ? 116 : 126;
    const PL_CX = PLX + PLW / 2, PL_BOTTOM = PLY + PLH;

    const gPilot = G();
    if (!reduced) gPilot.style.cssText = 'animation:sk6In .6s .1s ease both;opacity:0;';
    svg.appendChild(gPilot);

    gPilot.appendChild(R({
      x: PLX, y: PLY, width: PLW, height: PLH, rx: '16',
      fill: CO.white, stroke: CO.border, 'stroke-width': '1.5', filter: 'url(#sk6Card)',
    }));
    gPilot.appendChild(P({
      d: `M ${PLX} ${PLY + 16} A 16 16 0 0 1 ${PLX + 16} ${PLY} L ${PLX + PLW - 16} ${PLY}
          A 16 16 0 0 1 ${PLX + PLW} ${PLY + 16} L ${PLX + PLW} ${PLY + 38} L ${PLX} ${PLY + 38} Z`,
      fill: CO.off,
    }));
    gPilot.appendChild(P({
      d: `M ${PLX} ${PLY + 38} L ${PLX + PLW} ${PLY + 38}`,
      stroke: CO.border, 'stroke-width': '1', fill: 'none',
    }));
    gPilot.appendChild(R({ x: PLX + 16, y: PLY + 12, width: 3, height: 14, rx: '1.5', fill: CO.orange }));
    gPilot.appendChild(T({
      x: PLX + 28, y: PLY + 24, 'font-size': '11', 'font-weight': '700',
      'font-family': FD, fill: CO.dark, 'letter-spacing': '0.02em',
    }, 'Spark Pilot'));

    const ledOK = C({ cx: PLX + PLW - 62, cy: PLY + 19, r: '3.5', fill: CO.charging });
    if (!reduced) ledOK.style.animation = 'sk6Led 2.2s ease-in-out infinite';
    gPilot.appendChild(ledOK);
    gPilot.appendChild(T({
      x: PLX + PLW - 52, y: PLY + 23, 'font-size': '9', 'font-family': FB, fill: CO.light,
    }, 'En ligne'));
    gPilot.appendChild(T({
      x: PLX + 16, y: PLY + 58, 'font-size': '8.5', 'font-weight': '500',
      'font-family': FB, fill: CO.light, 'letter-spacing': '0.04em',
    }, IT('dashboard', 'Dashboard')));

    /* Mini graphe */
    const BAR_BOTTOM = PLY + PLH - (M ? 14 : 16);
    const BAR_MAX_H = M ? 42 : 52;
    const BAR_W = M ? 40 : 26, BAR_GAP = M ? 14 : 12, BAR_N = 6;
    const BAR_X0 = PLX + (PLW - (BAR_N * BAR_W + (BAR_N - 1) * BAR_GAP)) / 2;
    const barEls = [];
    [22, 38, 28, 46, 18, 34].forEach((bh, i) => {
      const bx = BAR_X0 + i * (BAR_W + BAR_GAP);
      const h = Math.round(bh * BAR_MAX_H / 52);
      gPilot.appendChild(R({
        x: bx, y: BAR_BOTTOM - BAR_MAX_H, width: BAR_W, height: BAR_MAX_H, rx: '4', fill: CO.off,
      }));
      const bar = R({
        x: bx, y: BAR_BOTTOM - h, width: BAR_W, height: h, rx: '4',
        fill: CO.orange, opacity: (0.45 + i * 0.09).toFixed(2),
      });
      if (!reduced) bar.style.animation = `sk6Bar ${1.6 + i * 0.2}s ease-in-out ${i * 0.12}s infinite`;
      gPilot.appendChild(bar);
      barEls.push(bar);
    });
    if (!reduced) {
      const sets = [[22,38,28,46,18,34],[34,22,44,30,38,20],[18,46,24,38,28,42],[42,26,48,18,34,30]];
      let si = 0;
      every(() => {
        si = (si + 1) % sets.length;
        sets[si].forEach((v, i) => {
          const h = Math.round(v * BAR_MAX_H / 52);
          barEls[i].setAttribute('y', BAR_BOTTOM - h);
          barEls[i].setAttribute('height', h);
        });
      }, 1100);
    }

    /* Carte "capacite" (tarification / paiement) — contenu commun */
    function capaCard(g, x, y, w, h, kind) {
      g.appendChild(R({
        x, y, width: w, height: h, rx: M ? '12' : '14',
        fill: CO.white, stroke: CO.border, 'stroke-width': '1.5', filter: 'url(#sk6Soft)',
      }));
      if (kind === 'tarif') {
        if (M) {
          g.appendChild(T({ x: x + 18, y: y + 28, 'font-size': '9.5', 'font-weight': '500',
            'font-family': FB, fill: CO.light }, IT('flexprice', 'Tarification flexible')));
          g.appendChild(T({ x: x + 18, y: y + 52, 'font-size': '17', 'font-weight': '800',
            'font-family': FD, fill: CO.dark, 'letter-spacing': '-0.02em' }, '0,24 €/kWh'));
          g.appendChild(T({ x: x + w - 18, y: y + 52, 'text-anchor': 'end', 'font-size': '8.5',
            'font-family': FB, fill: CO.light }, IT('flexprice_sub', 'défini dans Spark Pilot')));
        } else {
          const cx = x + w / 2;
          g.appendChild(T({ x: cx, y: y + 24, 'text-anchor': 'middle', 'font-size': '9.5',
            'font-weight': '500', 'font-family': FB, fill: CO.light }, IT('flexprice', 'Tarification flexible')));
          g.appendChild(T({ x: cx, y: y + 58, 'text-anchor': 'middle', 'font-size': '21',
            'font-weight': '800', 'font-family': FD, fill: CO.dark, 'letter-spacing': '-0.02em' }, '0,24 €/kWh'));
          g.appendChild(T({ x: cx, y: y + 78, 'text-anchor': 'middle', 'font-size': '8.5',
            'font-family': FB, fill: CO.light }, IT('flexprice_sub', 'défini dans Spark Pilot')));
        }
      } else {
        const cbW = M ? 92 : 112, cbH = M ? 30 : 34;
        const cbX = M ? (x + w - cbW - 18) : (x + (w - cbW) / 2);
        const cbY = M ? (y + h / 2 - cbH / 2) : (y + 34);
        if (M) {
          g.appendChild(T({ x: x + 18, y: y + 28, 'font-size': '9.5', 'font-weight': '500',
            'font-family': FB, fill: CO.light }, IT('autopay', 'Paiement automatisé')));
          g.appendChild(T({ x: x + 18, y: y + 50, 'font-size': '9',
            'font-family': FB, fill: CO.light }, 'CB · Apple Pay · Google Pay'));
        } else {
          const cx = x + w / 2;
          g.appendChild(T({ x: cx, y: y + 24, 'text-anchor': 'middle', 'font-size': '9.5',
            'font-weight': '500', 'font-family': FB, fill: CO.light }, IT('autopay', 'Paiement automatisé')));
          g.appendChild(T({ x: cx, y: y + 86, 'text-anchor': 'middle', 'font-size': '8.5',
            'font-family': FB, fill: CO.light }, 'CB · Apple Pay · Google Pay'));
        }
        g.appendChild(R({ x: cbX, y: cbY, width: cbW, height: cbH, rx: '6',
          fill: CO.off, stroke: CO.border, 'stroke-width': '1' }));
        g.appendChild(R({ x: cbX + 9, y: cbY + 8, width: 14, height: 11, rx: '2',
          fill: 'rgba(232,86,58,0.30)' }));
        g.appendChild(T({ x: cbX + 30, y: cbY + cbH / 2 + 4, 'font-size': '9.5', 'font-weight': '600',
          'font-family': 'ui-monospace,SFMono-Regular,Menlo,monospace', fill: CO.mid }, '•••• 4242'));
      }
    }

    const CONNECTIONS = [];

    if (!M) {
      /* ══ DESKTOP ══ */
      const CARD_Y = 296, CARD_W = 186, CARD_H = 192, VIS_H = 118;
      const BUS_Y = 250, SIDE_Y = 200, SIDE_W = 190, SIDE_H = 100;
      const TAR_CX = 128, PAY_CX = 772;
      const XS = [240, 450, 660];

      XS.forEach((cx, i) => {
        const pts = (cx === PL_CX)
          ? [[cx, CARD_Y], [cx, PL_BOTTOM]]
          : [[cx, CARD_Y], [cx, BUS_Y], [PL_CX, BUS_Y], [PL_CX, PL_BOTTOM]];
        CONNECTIONS.push({ d: ortho(pts, 14), color: stColor(PRODUCTS[i]), speed: 2.6 + i * 0.4 });
      });
      CONNECTIONS.push({ d: ortho([[TAR_CX, SIDE_Y], [TAR_CX, 130], [PLX, 130]], 14),
        color: CO.orange, speed: 3.4 });
      CONNECTIONS.push({ d: ortho([[PAY_CX, SIDE_Y], [PAY_CX, 130], [PLX + PLW, 130]], 14),
        color: CO.orange, speed: 3.8 });
      CONNECTIONS.forEach(c => connect(c.d, c.color, c.speed));

      [['tarif', TAR_CX, 0.3], ['pay', PAY_CX, 0.4]].forEach(([kind, cx, delay]) => {
        const g = G();
        if (!reduced) g.style.cssText = `animation:sk6In .55s ${delay}s ease both;opacity:0;`;
        capaCard(g, cx - SIDE_W / 2, SIDE_Y, SIDE_W, SIDE_H, kind);
        svg.appendChild(g);
      });

      PRODUCTS.forEach((p, i) => {
        const x = XS[i] - CARD_W / 2;
        const g = G();
        if (!reduced) g.style.cssText = `animation:sk6In .55s ${0.5 + i * 0.1}s ease both;opacity:0;`;
        g.appendChild(R({ x, y: CARD_Y, width: CARD_W, height: CARD_H, rx: '14',
          fill: CO.white, stroke: CO.border, 'stroke-width': '1.5', filter: 'url(#sk6Card)' }));
        g.appendChild(R({ x: x + 10, y: CARD_Y + 10, width: CARD_W - 20, height: VIS_H,
          rx: '10', fill: CO.off }));
        cutout(g, p.img, p.alt, x + 18, CARD_Y + 17, CARD_W - 36, VIS_H - 14);
        g.appendChild(T({ x: x + 16, y: CARD_Y + 150, 'font-size': '13', 'font-weight': '700',
          'font-family': FD, fill: CO.dark, 'letter-spacing': '-0.01em' }, p.label));
        g.appendChild(T({ x: x + 16, y: CARD_Y + 166, 'font-size': '9.5',
          'font-family': FB, fill: CO.light }, p.sub));
        const sy = CARD_Y + 184;
        const dot = C({ cx: x + 19, cy: sy - 3.5, r: '3.5', fill: stColor(p) });
        if (!reduced) dot.style.animation = `sk6Led ${2 + i * 0.3}s ease-in-out ${i * 0.25}s infinite`;
        g.appendChild(dot);
        g.appendChild(T({ x: x + 29, y: sy, 'font-size': '9.5', 'font-weight': '500',
          'font-family': FB, fill: stColor(p) }, stLabel(p)));
        svg.appendChild(g);
      });

    } else {
      /* ══ MOBILE ══ colonne portrait, rail vertical a gauche */
      const RAIL_X = 30, RAIL_Y = 144;
      const ROW_X = 56, ROW_W = W - ROW_X - 14, ROW_H = 74, ROW_GAP = 12;
      const ROW_Y0 = 168;
      const rowY = (i) => ROW_Y0 + i * (ROW_H + ROW_GAP);
      const rowMid = (i) => rowY(i) + ROW_H / 2;

      /* 5 lignes : 3 bornes puis les 2 capacites */
      const ROWS = [
        ...PRODUCTS.map((p, i) => ({ kind: 'product', p, i })),
        { kind: 'tarif' }, { kind: 'pay' },
      ];

      ROWS.forEach((row, i) => {
        const mid = rowMid(i);
        const d = ortho([[ROW_X, mid], [RAIL_X, mid], [RAIL_X, RAIL_Y], [PL_CX, RAIL_Y], [PL_CX, PL_BOTTOM]], 10);
        const color = row.kind === 'product' ? stColor(row.p) : CO.orange;
        CONNECTIONS.push({ d, color, speed: 2.8 + i * 0.35 });
      });
      CONNECTIONS.forEach(c => connect(c.d, c.color, c.speed));

      ROWS.forEach((row, i) => {
        const y = rowY(i);
        const g = G();
        if (!reduced) g.style.cssText = `animation:sk6In .5s ${0.3 + i * 0.08}s ease both;opacity:0;`;

        if (row.kind !== 'product') {
          capaCard(g, ROW_X, y, ROW_W, ROW_H, row.kind);
          svg.appendChild(g);
          return;
        }

        const p = row.p;
        g.appendChild(R({ x: ROW_X, y, width: ROW_W, height: ROW_H, rx: '12',
          fill: CO.white, stroke: CO.border, 'stroke-width': '1.5', filter: 'url(#sk6Soft)' }));
        /* vitrine carree a gauche */
        g.appendChild(R({ x: ROW_X + 8, y: y + 8, width: 58, height: ROW_H - 16, rx: '9', fill: CO.off }));
        cutout(g, p.img, p.alt, ROW_X + 12, y + 12, 50, ROW_H - 24);
        /* texte */
        const tx = ROW_X + 78;
        g.appendChild(T({ x: tx, y: y + 27, 'font-size': '12.5', 'font-weight': '700',
          'font-family': FD, fill: CO.dark, 'letter-spacing': '-0.01em' }, p.label));
        g.appendChild(T({ x: tx, y: y + 43, 'font-size': '9.5',
          'font-family': FB, fill: CO.light }, p.sub));
        const dot = C({ cx: tx + 3, cy: y + 56, r: '3.5', fill: stColor(p) });
        if (!reduced) dot.style.animation = `sk6Led ${2 + i * 0.3}s ease-in-out ${i * 0.25}s infinite`;
        g.appendChild(dot);
        g.appendChild(T({ x: tx + 13, y: y + 59.5, 'font-size': '9.5', 'font-weight': '500',
          'font-family': FB, fill: stColor(p) }, stLabel(p)));
        svg.appendChild(g);
      });
    }

    /* Flux de paquets */
    if (!reduced && CONNECTIONS.length) {
      let ci = 0;
      every(() => {
        const c = CONNECTIONS[ci];
        spawnPacket(c.d, c.color, M ? 1800 : 1500);
        ci = (ci + 1) % CONNECTIONS.length;
      }, M ? 900 : 760);
      later(() => spawnPacket(CONNECTIONS[0].d, CONNECTIONS[0].color, M ? 1800 : 1500), 300);
    }

    /* ── Load balancing ── */
    const lbDiv = document.createElement('div');
    lbDiv.style.cssText = `margin-top:${M ? 14 : 18}px;padding:0 2px;`;
    lbDiv.innerHTML = `
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px;font-family:${FB};">
        <span style="font-size:10.5px;color:${CO.light};letter-spacing:.04em;">Load balancing</span>
        <span id="sk6lbkw" style="font-size:10.5px;color:${CO.dark};font-weight:700;font-variant-numeric:tabular-nums;">55 kW / 100 kW</span>
      </div>
      <div style="width:100%;height:7px;background:${CO.off};border:1px solid ${CO.border};border-radius:4px;overflow:hidden;">
        <div id="sk6lbbar" style="width:55%;height:100%;background:linear-gradient(90deg,${CO.charging} 0%,${CO.orange} 70%,#DC2626 100%);border-radius:4px;transition:width .9s ease-out;"></div>
      </div>
    `;
    wrap.appendChild(lbDiv);

    if (!reduced) {
      const vals = [38, 55, 70, 46, 62, 80, 44, 58];
      let vi = 0;
      every(() => {
        vi = (vi + 1) % vals.length;
        const v = vals[vi];
        const bar = document.getElementById('sk6lbbar');
        const kw = document.getElementById('sk6lbkw');
        if (bar) bar.style.width = v + '%';
        if (kw) kw.textContent = v + ' kW / 100 kW';
      }, 2300);
    }

    /* ── Legende ── */
    const legDiv = document.createElement('div');
    legDiv.style.cssText = `display:flex;gap:${M ? '10px 16px' : '22px'};justify-content:center;flex-wrap:wrap;`
      + `margin-top:${M ? 14 : 18}px;padding-top:${M ? 12 : 16}px;border-top:1px solid ${CO.border};`
      + `font-family:${FB};font-size:${M ? 10.5 : 11}px;color:${CO.light};`;
    const dot = (c) => `<span style="width:8px;height:8px;border-radius:50%;background:${c};display:inline-block;flex-shrink:0;"></span>`;
    legDiv.innerHTML = `
      <span style="display:flex;align-items:center;gap:7px;">${dot(CO.charging)}${IT('charging', 'En charge')}</span>
      <span style="display:flex;align-items:center;gap:7px;">${dot(CO.available)}${IT('available', 'Disponible')}</span>
      <span style="display:flex;align-items:center;gap:7px;">${dot(CO.orange)}${IT('signal', 'Signal Spark Pilot')}</span>
      <span style="display:flex;align-items:center;gap:7px;">${dot(CO.offline)}${IT('offline', 'Hors ligne')}</span>
    `;
    wrap.appendChild(legDiv);
  }

  /* ── Bascule desktop / mobile sur franchissement du seuil ── */
  const mq = window.matchMedia('(max-width: 720px)');
  let current = null;
  const apply = () => {
    const mode = mq.matches ? 'mobile' : 'desktop';
    if (mode === current) return;
    current = mode;
    build(mode);
  };
  apply();
  if (mq.addEventListener) mq.addEventListener('change', apply);
  else if (mq.addListener) mq.addListener(apply);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('sparklin-interconnect')) setTimeout(initSparklinInterconnect, 200);
  });
} else {
  if (document.getElementById('sparklin-interconnect')) setTimeout(initSparklinInterconnect, 200);
}
