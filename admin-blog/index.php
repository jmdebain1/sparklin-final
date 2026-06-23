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
<title>Sparklin — Espace Rédaction</title>
<link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@500;600;700;800&family=Wix+Madefor+Text:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#F7F6F2;
  --bg2:#EEECEA;
  --white:#FFFFFF;
  --border:#E4E2DC;
  --border2:#D0CEC7;
  --orange:#E8563A;
  --orange2:#C94B31;
  --orange-bg:rgba(232,86,58,.07);
  --orange-bg2:rgba(232,86,58,.14);
  --text:#1A1916;
  --text2:#4A4844;
  --text3:#8A8780;
  --green:#15803D;
  --green-bg:#F0FDF4;
  --amber:#B45309;
  --amber-bg:#FFFBEB;
  --red:#DC2626;
  --red-bg:#FEF2F2;
  --blue:#1D4ED8;
  --blue-bg:#EFF6FF;
  --purple:#7C3AED;
  --purple-bg:#F5F3FF;
  --sidebar:220px;
  --header:56px;
  --fd:'Wix Madefor Display',system-ui,sans-serif;
  --fb:'Wix Madefor Text',system-ui,sans-serif;
  --fm:'JetBrains Mono',monospace;
  --r:10px;
  --r2:7px;
  --shadow:0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04);
  --shadow2:0 4px 16px rgba(0,0,0,.08),0 1px 3px rgba(0,0,0,.04);
}
html,body{height:100%;font-family:var(--fb);font-size:14px;line-height:1.6;color:var(--text);background:var(--bg);overflow:hidden}

/* ── SHELL ── */
.shell{display:grid;grid-template-columns:var(--sidebar) 1fr;grid-template-rows:var(--header) 1fr;height:100vh}
.topbar{grid-column:1/-1;background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 20px;z-index:100}
.sidebar{background:var(--white);border-right:1px solid var(--border);display:flex;flex-direction:column;overflow-y:auto;padding:12px 8px}
.main{overflow-y:auto;background:var(--bg)}

/* ── TOPBAR ── */
.tb-brand{display:flex;align-items:center;gap:8px;font-family:var(--fd);font-size:16px;font-weight:700;color:var(--text)}
.tb-brand-dot{width:8px;height:8px;border-radius:50%;background:var(--orange)}
.tb-badge{font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.08em;background:var(--orange-bg);color:var(--orange);padding:2px 7px;border-radius:100px;border:1px solid var(--orange-bg2)}
.tb-center{flex:1;display:flex;justify-content:center}
.tb-search{display:flex;align-items:center;gap:8px;background:var(--bg);border:1px solid var(--border);border-radius:var(--r2);padding:7px 12px;width:280px;transition:border-color .15s}
.tb-search:focus-within{border-color:var(--orange);background:var(--white)}
.tb-search input{background:none;border:none;outline:none;font-family:var(--fb);font-size:13px;color:var(--text);width:100%}
.tb-search input::placeholder{color:var(--text3)}
.tb-right{display:flex;align-items:center;gap:8px}
.tb-save{font-size:11px;color:var(--text3);font-family:var(--fm);display:none;align-items:center;gap:5px}
.tb-save.show{display:flex}
.save-dot{width:6px;height:6px;border-radius:50%;background:var(--green);animation:pulse 2s ease-in-out infinite}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}
.avatar{width:30px;height:30px;border-radius:50%;background:var(--orange-bg2);border:1.5px solid var(--orange);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--orange);cursor:pointer}

/* ── SIDEBAR ── */
.sb-section{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text3);padding:8px 10px 4px;margin-top:4px}
.sb-item{display:flex;align-items:center;gap:8px;padding:7px 10px;border-radius:var(--r2);color:var(--text2);cursor:pointer;font-size:13px;font-weight:500;transition:all .12s;border:none;background:none;width:100%;text-align:left;font-family:var(--fb);text-decoration:none}
.sb-item:hover{background:var(--bg);color:var(--text)}
.sb-item.active{background:var(--orange-bg);color:var(--orange);font-weight:600}
.sb-item svg{flex-shrink:0;opacity:.65}
.sb-item.active svg{opacity:1}
.sb-badge{margin-left:auto;font-size:10px;font-family:var(--fm);background:var(--bg2);color:var(--text3);padding:1px 5px;border-radius:4px}
.sb-badge.new{background:var(--orange-bg2);color:var(--orange)}
.sb-divider{height:1px;background:var(--border);margin:8px 4px}
.sb-new-btn{display:flex;align-items:center;justify-content:center;gap:6px;padding:9px;border-radius:var(--r2);background:var(--orange);color:#fff;font-size:13px;font-weight:600;cursor:pointer;border:none;width:100%;font-family:var(--fb);transition:background .15s;margin-bottom:8px}
.sb-new-btn:hover{background:var(--orange2)}

/* ── VIEWS ── */
.view{display:none;padding:28px 32px;flex-direction:column;gap:24px;min-height:100%}
.view.active{display:flex}

/* ── CARDS ── */
.card{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--shadow)}
.card-inner{padding:20px}

/* ── BTNS ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border-radius:var(--r2);font-size:13px;font-weight:600;font-family:var(--fb);cursor:pointer;border:none;transition:all .15s;white-space:nowrap;text-decoration:none}
.btn-primary{background:var(--orange);color:#fff}.btn-primary:hover{background:var(--orange2)}
.btn-secondary{background:var(--white);color:var(--text2);border:1px solid var(--border)}.btn-secondary:hover{border-color:var(--border2);color:var(--text);background:var(--bg)}
.btn-ghost{background:none;color:var(--text3);border:none;padding:6px 10px}.btn-ghost:hover{background:var(--bg);color:var(--text2)}
.btn-danger{background:var(--red-bg);color:var(--red);border:1px solid #FECACA}.btn-danger:hover{background:#FEE2E2}
.btn-ai{background:linear-gradient(135deg,#7C3AED,#6D28D9);color:#fff}.btn-ai:hover{background:linear-gradient(135deg,#6D28D9,#5B21B6);box-shadow:0 4px 16px rgba(124,58,237,.3)}
.btn-green{background:var(--green);color:#fff}.btn-green:hover{background:#166534}
.btn-sm{padding:5px 10px;font-size:12px}
.btn-icon{width:30px;height:30px;padding:0;justify-content:center;border-radius:var(--r2)}

/* ── STATUS ── */
.status{display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;padding:3px 8px;border-radius:100px;white-space:nowrap}
.status::before{content:'';width:5px;height:5px;border-radius:50%;flex-shrink:0}
.s-published{background:var(--green-bg);color:var(--green)}.s-published::before{background:var(--green)}
.s-draft{background:var(--bg2);color:var(--text3)}.s-draft::before{background:var(--text3)}
.s-scheduled{background:var(--blue-bg);color:var(--blue)}.s-scheduled::before{background:var(--blue)}
.s-review{background:var(--amber-bg);color:var(--amber)}.s-review::before{background:var(--amber)}

/* ── METRICS ── */
.metrics{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.metric{background:var(--white);border:1px solid var(--border);border-radius:var(--r);padding:18px 20px;box-shadow:var(--shadow);transition:box-shadow .2s}
.metric:hover{box-shadow:var(--shadow2)}
.metric-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}
.metric-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center}
.metric-trend{font-size:11px;font-weight:600;padding:2px 6px;border-radius:4px}
.t-up{background:var(--green-bg);color:var(--green)}
.t-down{background:var(--red-bg);color:var(--red)}
.t-flat{background:var(--bg2);color:var(--text3)}
.metric-val{font-family:var(--fd);font-size:1.8rem;font-weight:800;color:var(--text);line-height:1;margin-bottom:4px}
.metric-lbl{font-size:12px;color:var(--text3)}

/* ── PAGE HEADER ── */
.ph{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap}
.ph-title{font-family:var(--fd);font-size:22px;font-weight:800;color:var(--text);margin-bottom:2px}
.ph-sub{font-size:13px;color:var(--text3)}

/* ── ARTICLE TABLE ── */
.art-table{width:100%;border-collapse:collapse}
.art-table th{text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text3);padding:10px 16px;border-bottom:1px solid var(--border);background:var(--bg);user-select:none}
.art-table th:first-child{border-radius:var(--r) 0 0 0}
.art-table th:last-child{border-radius:0 var(--r) 0 0}
.art-row{cursor:pointer;transition:background .12s}
.art-row:hover{background:#FBF9F7}
.art-row td{padding:13px 16px;border-bottom:1px solid var(--border);vertical-align:middle}
.art-row:last-child td{border-bottom:none}
.art-check{width:16px;height:16px;border:1.5px solid var(--border2);border-radius:4px;cursor:pointer;accent-color:var(--orange)}
.art-title-cell{font-size:13px;font-weight:500;color:var(--text);margin-bottom:3px;max-width:320px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.art-slug{font-size:11px;font-family:var(--fm);color:var(--text3)}
.cat-pill{display:inline-flex;font-size:10px;font-weight:700;padding:2px 7px;border-radius:100px;text-transform:uppercase;letter-spacing:.05em}
.art-actions{display:flex;gap:4px;opacity:0;transition:opacity .12s}
.art-row:hover .art-actions{opacity:1}

/* ── FILTER PILLS ── */
.filters{display:flex;gap:6px;flex-wrap:wrap;align-items:center}
.fpill{padding:5px 12px;border:1px solid var(--border);border-radius:100px;font-size:12px;font-weight:500;color:var(--text3);background:var(--white);cursor:pointer;font-family:var(--fb);transition:all .12s}
.fpill:hover{border-color:var(--border2);color:var(--text2)}
.fpill.active{background:var(--orange);border-color:var(--orange);color:#fff}
.sort-btn{display:flex;align-items:center;gap:4px;padding:5px 10px;border:1px solid var(--border);border-radius:100px;font-size:12px;color:var(--text3);background:var(--white);cursor:pointer;font-family:var(--fb);transition:all .12s}
.sort-btn:hover{border-color:var(--border2);color:var(--text2)}

/* ── EDITOR LAYOUT ── */
.editor-wrap{display:grid;grid-template-columns:1fr 288px;gap:0;min-height:calc(100vh - var(--header));background:var(--white)}
.editor-body{display:flex;flex-direction:column;overflow-y:auto;border-right:1px solid var(--border)}
.editor-topbar{display:flex;align-items:center;gap:10px;padding:12px 20px;border-bottom:1px solid var(--border);position:sticky;top:0;background:var(--white);z-index:20}
.editor-content{padding:40px 56px 80px;max-width:760px;margin:0 auto;width:100%}
.editor-panel{overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:14px}

/* ── EDITOR TITLE ── */
.editor-title-input{font-family:var(--fd);font-size:2rem;font-weight:800;color:var(--text);border:none;outline:none;width:100%;background:none;resize:none;line-height:1.2;padding:0;margin-bottom:6px}
.editor-title-input::placeholder{color:var(--border2)}
.editor-slug-row{display:flex;align-items:center;gap:6px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid var(--border)}
.editor-slug-base{font-size:12px;color:var(--text3);font-family:var(--fm)}
.editor-slug-input{font-family:var(--fm);font-size:12px;color:var(--orange);border:none;outline:none;background:none;min-width:40px;border-bottom:1px dashed var(--orange-bg2);padding-bottom:1px}
.editor-slug-input:focus{border-bottom-color:var(--orange)}

/* ── RICH TEXT TOOLBAR ── */
.rte-bar{display:flex;align-items:center;gap:1px;flex-wrap:wrap}
.rte-btn{width:28px;height:28px;display:flex;align-items:center;justify-content:center;border:none;background:none;cursor:pointer;border-radius:5px;color:var(--text2);font-family:var(--fm);font-size:12px;font-weight:600;transition:all .12s}
.rte-btn:hover{background:var(--bg)}
.rte-btn.on{background:var(--orange-bg);color:var(--orange)}
.rte-sep{width:1px;height:18px;background:var(--border);margin:0 4px}
.rte-body{font-family:var(--fb);font-size:15px;line-height:1.85;color:var(--text);min-height:400px;outline:none;caret-color:var(--orange)}
.rte-body:empty::before{content:attr(data-ph);color:var(--border2);pointer-events:none}
.rte-body h2{font-family:var(--fd);font-size:1.4em;font-weight:700;margin:1.6em 0 .6em;color:var(--text);border-bottom:2px solid var(--border);padding-bottom:.3em}
.rte-body h3{font-family:var(--fd);font-size:1.15em;font-weight:600;margin:1.4em 0 .5em;color:var(--text)}
.rte-body p{margin:0 0 .9em}
.rte-body ul,.rte-body ol{margin:.5em 0 .9em;padding-left:1.4em}
.rte-body li{margin-bottom:.25em}
.rte-body blockquote{border-left:3px solid var(--orange);padding:.6em 1em;margin:1.2em 0;background:var(--orange-bg);border-radius:0 var(--r2) var(--r2) 0;font-style:italic;color:var(--text2)}
.rte-body a{color:var(--orange);text-decoration:underline}
.rte-body strong{font-weight:700}
.rte-body em{font-style:italic}
.rte-body code{font-family:var(--fm);font-size:.88em;background:var(--bg2);padding:1px 5px;border-radius:4px;color:var(--orange2)}
.rte-body pre{background:var(--text);color:#e2e8f0;padding:16px 20px;border-radius:var(--r);font-family:var(--fm);font-size:.85em;overflow-x:auto;margin:1em 0}
.rte-body hr{border:none;border-top:2px solid var(--border);margin:2em 0}
.rte-footer{display:flex;align-items:center;justify-content:space-between;padding:10px 0 0;border-top:1px solid var(--border);margin-top:20px}
.rte-stat{font-size:11px;font-family:var(--fm);color:var(--text3)}

/* ── WORD GOAL ── */
.word-goal-wrap{height:3px;background:var(--border);border-radius:100px;overflow:hidden;margin-top:4px}
.word-goal-bar{height:100%;border-radius:100px;transition:width .5s,background .3s}

/* ── FOCUS MODE ── */
body.focus-mode .sidebar,.body.focus-mode .editor-panel,.body.focus-mode .editor-topbar{display:none}
.focus-indicator{position:fixed;top:12px;left:50%;transform:translateX(-50%);background:var(--text);color:#fff;font-size:11px;font-family:var(--fm);padding:4px 12px;border-radius:100px;z-index:500;opacity:0;pointer-events:none;transition:opacity .3s}

/* ── PANEL SECTIONS ── */
.panel-section{display:flex;flex-direction:column;gap:8px}
.panel-label{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text3)}
.panel-card{background:var(--bg);border:1px solid var(--border);border-radius:var(--r2);padding:12px 14px}
.field-row{display:flex;flex-direction:column;gap:4px}
.field-row label{font-size:11px;font-weight:600;color:var(--text3)}
.field-row input,.field-row textarea,.field-row select{background:var(--white);border:1px solid var(--border);border-radius:6px;color:var(--text);font-family:var(--fb);font-size:12px;padding:7px 10px;outline:none;transition:border-color .15s;width:100%}
.field-row input:focus,.field-row textarea:focus,.field-row select:focus{border-color:var(--orange)}
.field-row textarea{resize:vertical;min-height:70px;line-height:1.6}
.field-row select option{background:var(--white)}
.char-hint{font-size:10px;font-family:var(--fm);color:var(--text3);text-align:right;transition:color .2s}
.ch-ok{color:var(--green)}
.ch-warn{color:var(--amber)}

/* ── SEO SCORE ── */
.seo-ring-wrap{display:flex;align-items:center;gap:12px;margin-bottom:10px}
.seo-ring{position:relative;width:52px;height:52px;flex-shrink:0}
.seo-ring svg{transform:rotate(-90deg)}
.seo-ring-val{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-family:var(--fd);font-size:14px;font-weight:800;color:var(--text)}
.seo-items{display:flex;flex-direction:column;gap:6px}
.seo-item{display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text2)}
.seo-item svg{flex-shrink:0}
.seo-ok svg{color:var(--green)}
.seo-warn svg{color:var(--amber)}
.seo-fail svg{color:var(--text3)}

/* ── AI PANEL ── */
.ai-panel{background:linear-gradient(135deg,var(--purple-bg),#EDE9FE);border:1px solid #DDD6FE;border-radius:var(--r2);padding:14px}
.ai-header{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:700;color:var(--purple);margin-bottom:12px}
.ai-modes{display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-bottom:10px}
.ai-mode{display:flex;flex-direction:column;gap:2px;padding:8px;background:var(--white);border:1.5px solid #DDD6FE;border-radius:6px;cursor:pointer;transition:all .15s}
.ai-mode:hover{border-color:#A78BFA}
.ai-mode.sel{border-color:var(--purple);background:#F5F3FF}
.ai-mode-name{font-size:11px;font-weight:700;color:var(--text2)}
.ai-mode-sub{font-size:10px;color:var(--text3)}
.ai-tones{display:flex;gap:4px;flex-wrap:wrap;margin-bottom:10px}
.ai-tone{padding:3px 8px;border-radius:100px;border:1px solid #DDD6FE;background:var(--white);font-size:10px;font-weight:600;color:var(--text3);cursor:pointer;font-family:var(--fb);transition:all .15s}
.ai-tone.active,.ai-tone:hover{background:#7C3AED;border-color:#7C3AED;color:#fff}
.ai-kw{display:flex;flex-wrap:wrap;gap:4px;margin-bottom:10px}
.ai-chip{display:inline-flex;align-items:center;gap:4px;font-size:10px;padding:2px 7px;border-radius:100px;background:#EDE9FE;border:1px solid #C4B5FD;color:#5B21B6;cursor:pointer}
.ai-chip:hover{background:#DDD6FE}
.gen-steps-wrap{margin:8px 0}
.gen-step{display:flex;align-items:center;gap:6px;font-size:11px;color:var(--text3);padding:3px 0;opacity:.4;transition:opacity .3s}
.gen-step.on{opacity:1;color:var(--text2)}
.gen-step.done{opacity:.6;color:var(--green)}
.gen-step.done::before,.gen-step.on::before{content:'';width:5px;height:5px;border-radius:50%;background:currentColor;flex-shrink:0}
.gen-step::before{content:'';width:5px;height:5px;border-radius:50%;background:var(--border2);flex-shrink:0}
.gen-prog{height:2px;background:var(--border);border-radius:100px;overflow:hidden;margin:6px 0}
.gen-prog-bar{height:100%;background:linear-gradient(90deg,#7C3AED,var(--orange));border-radius:100px;transition:width .5s cubic-bezier(.22,1,.36,1)}

/* ── PUBLISH PANEL ── */
.pub-tabs{display:flex;gap:0;background:var(--bg2);border-radius:6px;padding:2px;margin-bottom:10px}
.pub-tab{flex:1;text-align:center;padding:5px 8px;border-radius:5px;font-size:11px;font-weight:600;cursor:pointer;color:var(--text3);transition:all .15s}
.pub-tab.active{background:var(--white);color:var(--text);box-shadow:var(--shadow)}
.pub-meta{display:flex;flex-direction:column;gap:6px;margin-bottom:10px}
.pub-row{display:flex;align-items:center;justify-content:space-between}
.pub-row-l{font-size:11px;color:var(--text3)}
.pub-row-r{font-size:11px;font-weight:600;color:var(--text2)}
.pub-url{background:var(--bg);border:1px solid var(--border);border-radius:5px;padding:6px 8px;font-size:10px;font-family:var(--fm);color:var(--orange);word-break:break-all;margin-bottom:8px}
.og-preview{background:var(--white);border:1px solid var(--border);border-radius:6px;overflow:hidden;margin-bottom:10px}
.og-img{height:60px;background:linear-gradient(135deg,var(--orange-bg2),var(--orange-bg));display:flex;align-items:center;justify-content:center}
.og-body{padding:8px 10px}
.og-domain{font-size:10px;color:var(--text3);text-transform:uppercase;letter-spacing:.05em;margin-bottom:2px}
.og-title{font-size:12px;font-weight:600;color:var(--text);line-height:1.3;margin-bottom:2px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.og-desc{font-size:10px;color:var(--text3);overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.schedule-input{background:var(--white);border:1px solid var(--border);border-radius:6px;color:var(--text);font-family:var(--fb);font-size:12px;padding:7px 10px;outline:none;width:100%;margin-top:4px;display:none}
.schedule-input:focus{border-color:var(--orange)}
.schedule-input.show{display:block}

/* ── IMAGE DROP ── */
.img-drop{border:2px dashed var(--border2);border-radius:var(--r2);padding:20px;text-align:center;cursor:pointer;transition:all .2s}
.img-drop:hover,.img-drop.over{border-color:var(--orange);background:var(--orange-bg)}
.img-drop-icon{margin-bottom:6px;color:var(--text3)}
.img-drop p{font-size:12px;color:var(--text3)}
.img-drop small{font-size:10px;color:var(--text3);opacity:.7}
.img-thumb{position:relative;border-radius:var(--r2);overflow:hidden}
.img-thumb img{width:100%;height:80px;object-fit:cover;display:block}
.img-thumb-del{position:absolute;top:4px;right:4px;width:22px;height:22px;background:rgba(0,0,0,.5);border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff}

/* ── VERSIONS ── */
.version-item{display:flex;align-items:center;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border)}
.version-item:last-child{border-bottom:none}
.ver-time{font-size:11px;color:var(--text2)}
.ver-words{font-size:10px;font-family:var(--fm);color:var(--text3)}
.ver-restore{font-size:11px;color:var(--orange);cursor:pointer;border:none;background:none;font-family:var(--fb);padding:0}
.ver-restore:hover{text-decoration:underline}

/* ── ANALYTICS CHART ── */
.chart-container{position:relative;height:140px;background:var(--bg);border-radius:var(--r2);overflow:hidden;margin-bottom:4px}
.chart-bars{display:flex;align-items:flex-end;gap:3px;height:100%;padding:8px 8px 0}
.chart-bar{flex:1;border-radius:3px 3px 0 0;transition:height .8s cubic-bezier(.22,1,.36,1),opacity .2s;cursor:pointer;min-height:3px}
.chart-bar:hover{opacity:.8}
.chart-labels{display:flex;justify-content:space-between;padding:4px 8px 0;font-size:10px;font-family:var(--fm);color:var(--text3)}
.top-art{display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--border)}
.top-art:last-child{border-bottom:none}
.top-art-rank{width:20px;font-size:11px;font-family:var(--fm);color:var(--text3);text-align:center;flex-shrink:0}
.top-art-info{flex:1;min-width:0}
.top-art-title{font-size:12px;font-weight:500;color:var(--text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-bottom:3px}
.top-art-bar-wrap{height:3px;background:var(--border);border-radius:100px;overflow:hidden}
.top-art-bar{height:100%;background:var(--orange);border-radius:100px}
.top-art-views{font-size:11px;font-family:var(--fm);color:var(--text2);flex-shrink:0}

/* ── ACTIVITY ── */
.act-item{display:flex;align-items:flex-start;gap:10px;padding:9px 0;border-bottom:1px solid var(--border)}
.act-item:last-child{border-bottom:none}
.act-ico{width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.act-text{font-size:12px;color:var(--text2);flex:1;line-height:1.5}
.act-text strong{color:var(--text);font-weight:600}
.act-time{font-size:10px;font-family:var(--fm);color:var(--text3);flex-shrink:0}

/* ── SETTINGS ── */
.setting-row{display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)}
.setting-row:last-child{border-bottom:none}
.setting-info{flex:1;min-width:0}
.setting-label{font-size:13px;font-weight:500;color:var(--text);margin-bottom:2px}
.setting-desc{font-size:11px;color:var(--text3)}
.toggle{position:relative;width:36px;height:20px;flex-shrink:0}
.toggle input{opacity:0;width:0;height:0;position:absolute}
.toggle-track{display:block;width:36px;height:20px;background:var(--border2);border-radius:100px;cursor:pointer;transition:background .2s}
.toggle input:checked+.toggle-track{background:var(--orange)}
.toggle-thumb{position:absolute;top:2px;left:2px;width:16px;height:16px;background:#fff;border-radius:50%;transition:left .2s;box-shadow:0 1px 3px rgba(0,0,0,.2)}
.toggle input:checked~.toggle-thumb{left:18px}

/* ── TOAST ── */
.toast{position:fixed;bottom:20px;right:20px;z-index:1000;display:flex;flex-direction:column;gap:8px;max-width:300px}
.toast-item{display:flex;align-items:center;gap:10px;background:var(--text);color:#fff;border-radius:var(--r2);padding:10px 14px;font-size:12px;box-shadow:var(--shadow2);animation:slideup .3s cubic-bezier(.22,1,.36,1) both}
@keyframes slideup{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
.toast-dot2{width:6px;height:6px;border-radius:50%;flex-shrink:0}

/* ── MODAL ── */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.3);z-index:200;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .2s}
.modal-bg.open{opacity:1;pointer-events:all}
.modal{background:var(--white);border:1px solid var(--border);border-radius:var(--r);box-shadow:var(--shadow2);padding:24px;max-width:520px;width:95%;transform:scale(.97) translateY(8px);transition:transform .2s}
.modal-bg.open .modal{transform:none}
.modal-title{font-family:var(--fd);font-size:17px;font-weight:700;margin-bottom:4px}
.modal-sub{font-size:13px;color:var(--text3);margin-bottom:18px}

/* ── MISC ── */
.tag{display:inline-flex;align-items:center;gap:3px;font-size:10px;padding:2px 7px;border-radius:100px;background:var(--bg2);color:var(--text3);border:1px solid var(--border)}
.link-icon-btn{background:none;border:none;cursor:pointer;padding:4px;border-radius:4px;color:var(--text3);display:flex;align-items:center;transition:all .12s}
.link-icon-btn:hover{background:var(--bg);color:var(--text2)}
::-webkit-scrollbar{width:4px;height:4px}
::-webkit-scrollbar-thumb{background:var(--border2);border-radius:4px}
::-webkit-scrollbar-track{background:transparent}
.divider{height:1px;background:var(--border);margin:4px 0}
@keyframes spin{to{transform:rotate(360deg)}}

/* ── Interface Language Selector (topbar) ── */
.tb-ui-lang {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 0 4px;
  border-right: 1px solid var(--border);
  margin-right: 4px;
}
.tb-ui-lang-btn {
  background: none;
  border: none;
  padding: 4px 7px;
  border-radius: 6px;
  font-size: 12px;
  font-family: var(--fm);
  font-weight: 600;
  color: var(--text3);
  cursor: pointer;
  transition: background .15s, color .15s;
  line-height: 1;
}
.tb-ui-lang-btn.active {
  background: var(--bg2);
  color: var(--text1);
}
.tb-ui-lang-btn:hover:not(.active) {
  background: var(--bg2);
  color: var(--text2);
}

/* ── Article Language Selector (editor right panel) ── */
.art-lang-selector {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.art-lang-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 6px;
}
.art-lang-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
  padding: 8px 4px 6px;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  background: var(--bg1);
  cursor: pointer;
  transition: border-color .15s, background .15s;
  font-family: var(--fm);
}
.art-lang-btn:hover {
  border-color: var(--orange);
  background: rgba(232,86,58,.04);
}
.art-lang-btn.selected {
  border-color: var(--orange);
  background: rgba(232,86,58,.07);
}
.art-lang-btn .lang-flag {
  font-size: 18px;
  line-height: 1;
}
.art-lang-btn .lang-code {
  font-size: 10px;
  font-weight: 700;
  color: var(--text2);
  letter-spacing: .04em;
  text-transform: uppercase;
}
.art-lang-btn .lang-label {
  font-size: 9px;
  color: var(--text3);
  text-align: center;
  line-height: 1.2;
}
.art-lang-btn.selected .lang-code {
  color: var(--orange);
}

/* ── Language badge in article list ── */
.lang-badge {
  display: inline-flex;
  align-items: center;
  gap: 3px;
  font-size: 10px;
  font-family: var(--fm);
  font-weight: 600;
  padding: 2px 6px;
  border-radius: 4px;
  background: var(--bg2);
  color: var(--text2);
  letter-spacing: .03em;
}
.lang-badge .lbf { font-size: 12px; line-height: 1; }

/* ── URL preview with lang prefix ── */
.pub-url-lang {
  color: var(--orange);
  font-weight: 700;
}

/* ── Translation matrix (articles view) ── */
.trans-matrix {
  margin-top: 20px;
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
}
.trans-matrix-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  background: var(--bg2);
  font-size: 13px;
  font-weight: 700;
  color: var(--text1);
  border-bottom: 1px solid var(--border);
}
.trans-row {
  display: grid;
  grid-template-columns: 1fr repeat(7, 36px);
  gap: 0;
  align-items: center;
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
  font-size: 12px;
}
.trans-row:last-child { border-bottom: none; }
.trans-row:hover { background: var(--bg2); }
.trans-row .tr-title { color: var(--text1); font-weight: 500; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.trans-cell {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  cursor: pointer;
}
.trans-cell .tc-ok { color: #16A34A; }
.trans-cell .tc-miss { color: var(--border); }
.trans-cell .tc-miss:hover { color: var(--orange); }
.trans-header-cell {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  opacity: .7;
}

</style>

<!-- ══ AUTH GUARD ═══════════════════════════════════════════
     Vérifie la session avant d'afficher le dashboard.
     Si pas de session valide → redirect login.html
     Si token magique dans URL → échange contre session token.
     ══════════════════════════════════════════════════════ -->
<script>
(function() {
  'use strict';

  var SESSION_KEY   = 'sk_admin_session';
  var SESSION_EMAIL = 'sk_admin_email';
  var SESSION_EXP   = 'sk_admin_exp';
  var VERIFY_URL    = '/api/verify-token.php';
  var LOGIN_URL     = '/admin-blog/login.html';

  /* ── Vérifie si la session locale est valide ── */
  function isSessionValid() {
    var token = sessionStorage.getItem(SESSION_KEY) || localStorage.getItem(SESSION_KEY);
    var exp   = parseInt(sessionStorage.getItem(SESSION_EXP) || localStorage.getItem(SESSION_EXP) || '0', 10);
    return token && Date.now() < exp;
  }

  /* ── Enregistre la session ── */
  function saveSession(sessionToken, email, expiresInSeconds) {
    var exp = Date.now() + expiresInSeconds * 1000;
    sessionStorage.setItem(SESSION_KEY,   sessionToken);
    sessionStorage.setItem(SESSION_EMAIL, email);
    sessionStorage.setItem(SESSION_EXP,   String(exp));
    // Aussi dans localStorage pour persistence onglets
    localStorage.setItem(SESSION_KEY,   sessionToken);
    localStorage.setItem(SESSION_EMAIL, email);
    localStorage.setItem(SESSION_EXP,   String(exp));
  }

  /* ── Montre le dashboard (retire le masque de chargement) ── */
  function showDashboard(email) {
    document.getElementById('auth-loading').style.display = 'none';
    document.getElementById('app-shell').style.display = 'block';
    // Affiche l'email de l'utilisateur dans l'avatar
    var av = document.getElementById('user-avatar-label');
    if (av && email) av.textContent = email.charAt(0).toUpperCase();
    var ue = document.getElementById('user-email-label');
    if (ue && email) ue.textContent = email;
  }

  /* ── Redirige vers login ── */
  function redirectLogin(reason) {
    console.log('[auth] redirect to login —', reason);
    window.location.replace(LOGIN_URL);
  }

  /* ── Main auth flow ── */
  async function checkAuth() {
    // 1. Token magique dans l'URL ?
    var urlParams = new URLSearchParams(window.location.search);
    var magicToken = urlParams.get('token');

    if (magicToken) {
      // Nettoie l'URL immédiatement (sécurité)
      history.replaceState(null, '', window.location.pathname);

      try {
        var res  = await fetch(VERIFY_URL + '?token=' + encodeURIComponent(magicToken));
        var data = await res.json();

        if (res.ok && data.ok) {
          saveSession(data.sessionToken, data.email, data.expiresIn);
          showDashboard(data.email);
        } else {
          // Token invalide ou expiré → login avec message
          sessionStorage.setItem('sk_auth_error', data.error || 'Lien invalide.');
          redirectLogin('token_invalid');
        }
      } catch (err) {
        console.error('[auth] verify error:', err);
        // En cas d'erreur réseau, vérifie session existante
        if (isSessionValid()) {
          var email = localStorage.getItem(SESSION_EMAIL) || '';
          showDashboard(email);
        } else {
          redirectLogin('network_error');
        }
      }
      return;
    }

    // 2. Session locale valide ?
    if (isSessionValid()) {
      var email = localStorage.getItem(SESSION_EMAIL) || '';
      showDashboard(email);
      return;
    }

    // 3. Pas de session → login
    redirectLogin('no_session');
  }

  // Lance la vérification dès le chargement du DOM
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', checkAuth);
  } else {
    checkAuth();
  }

  // Expose logout
  window.adminLogout = function() {
    sessionStorage.clear();
    localStorage.removeItem('sk_admin_session');
    localStorage.removeItem('sk_admin_email');
    localStorage.removeItem('sk_admin_exp');
    redirectLogin('logout');
  };
})();
</script>
</head>
<body>

<!-- ══ AUTH LOADING SCREEN ══════════════════════════════════ -->
<div id="auth-loading" style="
  position:fixed;inset:0;
  background:#F7F6F2;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:16px;z-index:9999;font-family:'Wix Madefor Text',sans-serif;
">
  <div style="font-family:'Wix Madefor Display',sans-serif;font-size:24px;font-weight:800;color:#E8563A;">sparklin</div>
  <div style="width:32px;height:32px;border:3px solid rgba(232,86,58,.2);border-top-color:#E8563A;border-radius:50%;animation:ld-spin .7s linear infinite;"></div>
  <div style="font-size:13px;color:#8A8780;">Vérification de la session…</div>
</div>
<style>
  @keyframes ld-spin{to{transform:rotate(360deg)}}
  #app-shell{display:none;}
</style>


<div id="app-shell"><div class="shell">

<!-- ══ TOPBAR ══════════════════════════════════════════════════ -->
<header class="topbar">
  <div class="tb-brand">
    <div class="tb-brand-dot"></div>
    sparklin
    <span class="tb-badge">Rédaction</span>
  </div>
  <div class="tb-center">
    <div class="tb-search">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24" style="color:var(--text3);flex-shrink:0"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.35-4.35"/></svg>
      <input id="search-input" placeholder="Rechercher…" oninput="liveSearch(this.value)"/>
      <kbd style="font-size:10px;font-family:var(--fm);color:var(--text3);background:var(--bg2);padding:1px 4px;border-radius:3px">⌘K</kbd>
    </div>
  </div>
  <div class="tb-right">
    <div class="tb-ui-lang" id="tb-ui-lang" title="Langue de l'interface">
      <button class="tb-ui-lang-btn active" data-ui-lang="fr" onclick="setUiLang('fr',this)">FR</button>
      <button class="tb-ui-lang-btn" data-ui-lang="en" onclick="setUiLang('en',this)">EN</button>
      <button class="tb-ui-lang-btn" data-ui-lang="de" onclick="setUiLang('de',this)">DE</button>
      <button class="tb-ui-lang-btn" data-ui-lang="es" onclick="setUiLang('es',this)">ES</button>
    </div>
    <div class="tb-save" id="autosave-indicator">
      <div class="save-dot"></div>
      Sauvegardé
    </div>
    <button class="btn btn-ghost btn-sm" onclick="showView('settings')">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    </button>
    <a class="btn btn-ghost btn-sm" href="/blog/" target="_blank">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
      Voir le blog
    </a>
    <div class="avatar" title="Admin Sparklin" id="user-avatar-label">A</div>
    <button class="btn btn-ghost btn-sm" onclick="adminLogout()" title="Déconnexion" style="font-size:12px;padding:5px 10px;">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
    </button>
  </div>
</header>

<!-- ══ SIDEBAR ══════════════════════════════════════════════════ -->
<aside class="sidebar">
  <button class="sb-new-btn" onclick="openEditor()">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
    Nouvel article
  </button>
  <div class="sb-section">Navigation</div>
  <button class="sb-item active" id="nav-dashboard" onclick="showView('dashboard')">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></svg>
    Tableau de bord
  </button>
  <button class="sb-item" id="nav-articles" onclick="showView('articles')">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
    Articles
    <span class="sb-badge" id="sb-count">7</span>
  </button>
  <button class="sb-item" id="nav-editor" onclick="openEditor()">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"/></svg>
    Éditeur
  </button>
  <div class="sb-section">Contenu</div>
  <button class="sb-item" id="nav-analytics" id="nav-analytics" onclick="showView('analytics')">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    Analytiques
  </button>
  <button class="sb-item" id="nav-settings" id="nav-settings" onclick="showView('settings')">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
    Paramètres
  </button>
</aside>

<!-- ══ MAIN ════════════════════════════════════════════════════ -->
<main class="main">

<!-- ─── DASHBOARD ────────────────────────────────────────────── -->
<div class="view active" id="view-dashboard">
  <div class="ph">
    <div><div class="ph-title">Tableau de bord</div><div class="ph-sub" id="dash-date"></div></div>
    <button class="btn btn-primary btn-sm" onclick="openEditor()">
      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
      Nouvel article
    </button>
  </div>

  <div class="metrics">
    <div class="metric">
      <div class="metric-row"><div class="metric-icon" style="background:var(--green-bg)"><svg width="15" height="15" fill="none" stroke="var(--green)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div><span class="metric-trend t-up">+2 mois</span></div>
      <div class="metric-val" id="m-pub">4</div><div class="metric-lbl">Publiés</div>
    </div>
    <div class="metric">
      <div class="metric-row"><div class="metric-icon" style="background:var(--bg2)"><svg width="15" height="15" fill="none" stroke="var(--text3)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"/></svg></div><span class="metric-trend t-flat">Stable</span></div>
      <div class="metric-val" id="m-draft">2</div><div class="metric-lbl">Brouillons</div>
    </div>
    <div class="metric">
      <div class="metric-row"><div class="metric-icon" style="background:var(--blue-bg)"><svg width="15" height="15" fill="none" stroke="var(--blue)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><span class="metric-trend t-flat">1 prévu</span></div>
      <div class="metric-val" id="m-sched">1</div><div class="metric-lbl">Planifiés</div>
    </div>
    <div class="metric">
      <div class="metric-row"><div class="metric-icon" style="background:var(--orange-bg)"><svg width="15" height="15" fill="none" stroke="var(--orange)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div><span class="metric-trend t-up">+18 %</span></div>
      <div class="metric-val">3 284</div><div class="metric-lbl">Vues ce mois</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
    <div class="card">
      <div class="card-inner" style="padding-bottom:0">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
          <div style="font-size:14px;font-weight:700;color:var(--text)">Articles récents</div>
          <button class="btn btn-ghost btn-sm" onclick="showView('articles')">Tous →</button>
        </div>
      </div>
      <table class="art-table" id="dash-table"><thead><tr><th style="width:40px"><input type="checkbox" class="art-check" onchange="toggleAll(this)"/></th><th>Titre</th><th>Statut</th><th>Vues</th><th></th></tr></thead><tbody id="dash-tbody"></tbody></table>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card">
        <div class="card-inner">
          <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:14px">Vues · 7 jours</div>
          <div class="chart-container" id="dash-chart"></div>
          <div class="chart-labels"><span>Lun</span><span>Dim</span></div>
        </div>
      </div>
      <div class="card">
        <div class="card-inner">
          <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:12px">Activité</div>
          <div id="dash-activity"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ─── ARTICLES LIST ─────────────────────────────────────────── -->
<div class="view" id="view-articles">
  <div class="ph">
    <div><div class="ph-title">Articles</div><div class="ph-sub" id="art-count-sub">7 articles</div></div>
    <div style="display:flex;gap:8px">
      <button class="btn btn-secondary btn-sm" id="batch-btn" style="display:none" onclick="batchAction()">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        Action (<span id="batch-count">0</span>)
      </button>
      <button class="btn btn-primary btn-sm" onclick="openEditor()">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Nouveau
      </button>
    </div>
  </div>
  <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
    <div class="filters" id="art-filters">
      <button class="fpill active" data-f="all" onclick="setFilter('all',this)">Tous <span style="opacity:.6">(7)</span></button>
      <!-- lang filters -->
      <div style="width:1px;height:16px;background:var(--border);margin:0 4px;align-self:center;"></div>
      <button class="fpill" data-f="lang-fr" onclick="setLangFilter('fr',this)" title="Français">🇫🇷</button>
      <button class="fpill" data-f="lang-en" onclick="setLangFilter('en',this)" title="English">🇬🇧</button>
      <button class="fpill" data-f="lang-de" onclick="setLangFilter('de',this)" title="Deutsch">🇩🇪</button>
      <button class="fpill" data-f="lang-es" onclick="setLangFilter('es',this)" title="Español">🇪🇸</button>
      <button class="fpill" data-f="published" onclick="setFilter('published',this)">Publiés <span style="opacity:.6">(4)</span></button>
      <button class="fpill" data-f="draft" onclick="setFilter('draft',this)">Brouillons <span style="opacity:.6">(2)</span></button>
      <button class="fpill" data-f="scheduled" onclick="setFilter('scheduled',this)">Planifiés <span style="opacity:.6">(1)</span></button>
    </div>
    <div style="display:flex;gap:6px">
      <button class="sort-btn" onclick="sortBy('date')">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        Date
      </button>
      <button class="sort-btn" onclick="sortBy('views')">Vues</button>
    </div>
  </div>
  <div class="card" style="overflow:hidden;padding:0">
    <table class="art-table" id="art-table">
      <thead>
        <tr>
          <th style="width:40px"><input type="checkbox" class="art-check" onchange="toggleAll(this)"/></th>
          <th>Titre</th>
          <th style="width:60px" id="col-lang-header">Langue</th>
          <th style="width:110px">Catégorie</th>
          <th style="width:100px">Statut</th>
          <th style="width:90px">Date</th>
          <th style="width:80px;text-align:right">Vues</th>
          <th style="width:80px"></th>
        </tr>
      </thead>
      <tbody id="art-tbody"></tbody>
    </table>
  </div>
</div>

<!-- ─── EDITOR ───────────────────────────────────────────────── -->
<div class="view" id="view-editor" style="padding:0;gap:0;flex-direction:row">
  <div class="editor-wrap">
    <!-- TOOLBAR -->
    <div class="editor-body">
      <div class="editor-topbar">
        <button class="btn btn-ghost btn-sm" onclick="showView('articles')" style="padding:5px 8px">
          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
          Articles
        </button>
        <div class="divider" style="width:1px;height:20px;margin:0 4px;background:var(--border)"></div>
        <div class="rte-bar" id="rte-toolbar">
          <button class="rte-btn" title="Gras (Ctrl+B)" onmousedown="e=>{e.preventDefault();fmt('bold')}"><b>B</b></button>
          <button class="rte-btn" title="Italique (Ctrl+I)" onmousedown="e=>{e.preventDefault();fmt('italic')}"><i>I</i></button>
          <button class="rte-btn" title="Souligné" onmousedown="e=>{e.preventDefault();fmt('underline')}"><u>U</u></button>
          <button class="rte-btn" title="Barré" onmousedown="e=>{e.preventDefault();fmt('strikeThrough')}"><s>S</s></button>
          <button class="rte-btn" title="Code" onmousedown="e=>{e.preventDefault();wrapCode()}">{ }</button>
          <div class="rte-sep"></div>
          <button class="rte-btn" title="Titre H2" onmousedown="e=>{e.preventDefault();fmt('formatBlock','h2')}">H2</button>
          <button class="rte-btn" title="Titre H3" onmousedown="e=>{e.preventDefault();fmt('formatBlock','h3')}">H3</button>
          <button class="rte-btn" title="Paragraphe" onmousedown="e=>{e.preventDefault();fmt('formatBlock','p')}">¶</button>
          <div class="rte-sep"></div>
          <button class="rte-btn" title="Liste à puces" onmousedown="e=>{e.preventDefault();fmt('insertUnorderedList')}">•—</button>
          <button class="rte-btn" title="Liste numérotée" onmousedown="e=>{e.preventDefault();fmt('insertOrderedList')}">1.</button>
          <button class="rte-btn" title="Citation" onmousedown="e=>{e.preventDefault();fmt('formatBlock','blockquote')}">"</button>
          <button class="rte-btn" title="Séparateur" onmousedown="e=>{e.preventDefault();insertHR()}">—</button>
          <div class="rte-sep"></div>
          <button class="rte-btn" title="Lien" onmousedown="e=>{e.preventDefault();insertLink()}">🔗</button>
          <button class="rte-btn" title="Image" onmousedown="e=>{e.preventDefault();document.getElementById('rte-img-input').click()}">🖼</button>
          <input type="file" id="rte-img-input" accept="image/*" style="display:none" onchange="insertImage(this)"/>
          <div class="rte-sep"></div>
          <button class="rte-btn" title="Annuler (Ctrl+Z)" onmousedown="e=>{e.preventDefault();fmt('undo')}">↩</button>
          <button class="rte-btn" title="Rétablir (Ctrl+Y)" onmousedown="e=>{e.preventDefault();fmt('redo')}">↪</button>
        </div>
        <div style="margin-left:auto;display:flex;align-items:center;gap:6px">
          <div class="tb-save show" id="editor-save-status">
            <div class="save-dot"></div>
            <span id="editor-save-text">Brouillon</span>
          </div>
          <button class="btn btn-ghost btn-sm" onclick="toggleFocusMode()" title="Mode focus (F11)">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
          </button>
        </div>
      </div>

      <!-- CONTENT AREA -->
      <div class="editor-content">
        <textarea id="ed-title" class="editor-title-input" placeholder="Titre de l'article…" rows="2" oninput="onTitle(this.value)"></textarea>
        <div class="editor-slug-row">
          <span class="editor-slug-base">sparklin.io/blog/</span>
          <input id="ed-slug" class="editor-slug-input" value="" placeholder="slug-url" oninput="onSlug(this.value)" size="30"/>
          <button class="link-icon-btn" title="Copier l'URL" onclick="copySlug()">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          </button>
        </div>
        <div id="rte-editor" class="rte-body" contenteditable="true"
          data-ph="Commencez à écrire votre article… Utilisez la barre d'outils ci-dessus ou le panneau IA à droite pour générer du contenu."
          oninput="onEditorInput()" onkeydown="handleShortcuts(event)"></div>
        <div class="rte-footer">
          <span class="rte-stat" id="rte-words">0 mot · 0 min</span>
          <div style="display:flex;align-items:center;gap:8px">
            <span class="rte-stat" id="rte-level"></span>
            <span class="rte-stat" id="rte-goal-text"></span>
          </div>
        </div>
        <div class="word-goal-wrap"><div class="word-goal-bar" id="word-goal-bar" style="width:0%"></div></div>
      </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="editor-panel" id="editor-panel">

      <!-- PUBLISH -->
      <div class="panel-section">
        <div class="panel-label" id="lbl-pub-label">Publication</div>
        <div class="panel-card">
          <div class="pub-tabs" id="pub-tabs">
            <div class="pub-tab active" onclick="setPubTab('now',this)">Maintenant</div>
            <div class="pub-tab" onclick="setPubTab('sched',this)">Planifier</div>
            <div class="pub-tab" onclick="setPubTab('draft',this)">Brouillon</div>
          </div>
          <div class="pub-meta">
            <div class="pub-row"><span class="pub-row-l">Statut</span><span id="pub-status" class="status s-draft">Brouillon</span></div>
            <div class="pub-row"><span class="pub-row-l">Visibilité</span><span class="pub-row-r">Public</span></div>
            <div class="pub-row"><span class="pub-row-l">Auteur</span><span class="pub-row-r">Admin Sparklin</span></div>
          </div>
          <div class="pub-url" id="pub-url-preview">sparklin.io/<span class="pub-url-lang" id="pub-url-lang-prefix">fr</span>/blog/<span id="pub-slug-preview">…</span></div>
          <input type="datetime-local" id="schedule-dt" class="schedule-input" onchange="onScheduleChange(this.value)"/>
          <div class="og-preview">
            <div class="og-img">
              <svg width="24" height="24" fill="none" stroke="var(--orange)" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24" opacity=".5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <div class="og-body">
              <div class="og-domain">sparklin.io</div>
              <div class="og-title" id="og-title">Titre de votre article</div>
              <div class="og-desc" id="og-desc">La méta description apparaîtra ici…</div>
            </div>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
            <button class="btn btn-secondary btn-sm" style="justify-content:center" onclick="saveDraft()">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13"/><polyline points="7 3 7 8 15 8"/></svg>
              Brouillon
            </button>
            <button class="btn btn-primary btn-sm" id="pub-main-btn" style="justify-content:center" onclick="publishNow()">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              Publier
            </button>
          </div>
        </div>
      </div>

      <!-- META / SEO -->
      <div class="panel-section">
        <div class="panel-label">SEO</div>
        <div class="panel-card" style="display:flex;flex-direction:column;gap:10px">
          <div class="field-row">
            <label>Méta description</label>
            <textarea id="ed-meta" rows="3" placeholder="120–160 caractères…" oninput="onMeta(this.value)"></textarea>
            <div class="char-hint" id="meta-hint">0 / 160</div>
          </div>
          <div class="field-row">
            <label>Mot-clé principal</label>
            <input type="text" id="ed-focus-kw" placeholder="Ex : recharge IRVE entreprise" oninput="updateSeo()"/>
          </div>
          <div class="seo-ring-wrap">
            <div class="seo-ring">
              <svg width="52" height="52" viewBox="0 0 52 52">
                <circle cx="26" cy="26" r="20" fill="none" stroke="var(--border)" stroke-width="5"/>
                <circle cx="26" cy="26" r="20" fill="none" stroke="var(--orange)" stroke-width="5" stroke-linecap="round" id="seo-circle" stroke-dasharray="125.6" stroke-dashoffset="125.6" style="transition:stroke-dashoffset .8s,stroke .3s"/>
              </svg>
              <div class="seo-ring-val" id="seo-score-val">—</div>
            </div>
            <div style="font-size:12px;color:var(--text3);line-height:1.5">Score SEO<br><strong id="seo-label" style="color:var(--text2)">En attente</strong></div>
          </div>
          <div class="seo-items" id="seo-checks"></div>
        </div>
      </div>

      <!-- ARTICLE LANGUAGE -->
      <div class="panel-section" id="panel-article-lang">
        <div class="panel-label" id="lbl-article-lang">Langue de l'article</div>
        <div class="panel-card">
          <div class="art-lang-selector">
            <div class="art-lang-grid">
              <button class="art-lang-btn selected" data-art-lang="fr" onclick="setArtLang('fr',this)">
                <span class="lang-flag">🇫🇷</span>
                <span class="lang-code">FR</span>
                <span class="lang-label">Français</span>
              </button>
              <button class="art-lang-btn" data-art-lang="en" onclick="setArtLang('en',this)">
                <span class="lang-flag">🇬🇧</span>
                <span class="lang-code">EN</span>
                <span class="lang-label">English</span>
              </button>
              <button class="art-lang-btn" data-art-lang="de" onclick="setArtLang('de',this)">
                <span class="lang-flag">🇩🇪</span>
                <span class="lang-code">DE</span>
                <span class="lang-label">Deutsch</span>
              </button>
              <button class="art-lang-btn" data-art-lang="es" onclick="setArtLang('es',this)">
                <span class="lang-flag">🇪🇸</span>
                <span class="lang-code">ES</span>
                <span class="lang-label">Español</span>
              </button>
              <button class="art-lang-btn" data-art-lang="th" onclick="setArtLang('th',this)">
                <span class="lang-flag">🇹🇭</span>
                <span class="lang-code">TH</span>
                <span class="lang-label">ภาษาไทย</span>
              </button>
              <button class="art-lang-btn" data-art-lang="ms" onclick="setArtLang('ms',this)">
                <span class="lang-flag">🇲🇾</span>
                <span class="lang-code">MS</span>
                <span class="lang-label">Melayu</span>
              </button>
              <button class="art-lang-btn" data-art-lang="id" onclick="setArtLang('id',this)">
                <span class="lang-flag">🇮🇩</span>
                <span class="lang-code">ID</span>
                <span class="lang-label">Indonesia</span>
              </button>
            </div>
            <div id="art-lang-info" style="font-size:11px;color:var(--text3);line-height:1.5;padding:8px 10px;background:var(--bg2);border-radius:6px;">
              Publié sur <strong id="art-lang-url-hint">sparklin.io/blog/</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- SETTINGS -->
      <div class="panel-section">
        <div class="panel-label">Paramètres article</div>
        <div class="panel-card" style="display:flex;flex-direction:column;gap:10px">
          <div class="field-row">
            <label>Catégorie</label>
            <select id="ed-cat">
              <option value="">— Sélectionner —</option>
              <option value="reglementation">Réglementation IRVE</option>
              <option value="technique">Guide technique</option>
              <option value="finance">Finance & Remboursement</option>
              <option value="produit">Nos produits</option>
              <option value="cas">Cas client</option>
              <option value="news">Actualité</option>
            </select>
          </div>
          <div class="field-row">
            <label>Tags</label>
            <input type="text" id="ed-tags" placeholder="IRVE, LOM, load balancing…"/>
          </div>
          <div class="field-row">
            <label>Image de couverture</label>
            <div class="img-drop" id="img-drop-zone" onclick="document.getElementById('cover-input').click()" ondragover="e=>{e.preventDefault();this.classList.add('over')}" ondragleave="this.classList.remove('over')" ondrop="handleDrop(event)">
              <div id="img-drop-content">
                <div class="img-drop-icon" style="display:flex;justify-content:center">
                  <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <p>Cliquer ou glisser une image</p>
                <small>JPG, PNG, WebP · Max 5 MB</small>
              </div>
            </div>
            <input type="file" id="cover-input" accept="image/*" style="display:none" onchange="handleCover(this)"/>
          </div>
          <div class="field-row">
            <label>Objectif mots</label>
            <select id="ed-wordgoal" onchange="updateWordGoal(this.value)">
              <option value="0">Pas d'objectif</option>
              <option value="300">Court — 300 mots</option>
              <option value="600" selected>Moyen — 600 mots</option>
              <option value="1200">Long — 1 200 mots</option>
              <option value="2000">Article complet — 2 000 mots</option>
            </select>
          </div>
        </div>
      </div>

      <!-- AI PANEL -->
      <div class="panel-section">
        <div class="panel-label">Intelligence artificielle</div>
        <div class="ai-panel">
          <div class="ai-header">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="m12 2-3.5 7H3l5.5 4-2 7L12 16l5.5 4-2-7L21 9h-5.5L12 2z"/></svg>
            Générer avec l'IA
          </div>
          <div class="ai-modes">
            <div class="ai-mode sel" id="ai-full" onclick="setAiMode('full')"><div class="ai-mode-name">Article complet</div><div class="ai-mode-sub">Intro + corps + conclusion</div></div>
            <div class="ai-mode" id="ai-intro" onclick="setAiMode('intro')"><div class="ai-mode-name">Introduction</div><div class="ai-mode-sub">Accroche seule</div></div>
            <div class="ai-mode" id="ai-outline" onclick="setAiMode('outline')"><div class="ai-mode-name">Plan</div><div class="ai-mode-sub">H2 + structure</div></div>
            <div class="ai-mode" id="ai-meta" onclick="setAiMode('meta')"><div class="ai-mode-name">SEO auto</div><div class="ai-mode-sub">Titre + méta</div></div>
          </div>
          <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#7C3AED;margin-bottom:6px">Ton</div>
          <div class="ai-tones">
            <button class="ai-tone active" data-t="expert" onclick="setTone(this)">Expert</button>
            <button class="ai-tone" data-t="simple" onclick="setTone(this)">Accessible</button>
            <button class="ai-tone" data-t="commercial" onclick="setTone(this)">Commercial</button>
            <button class="ai-tone" data-t="edu" onclick="setTone(this)">Pédagogique</button>
          </div>
          <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#7C3AED;margin:8px 0 6px">Mots-clés</div>
          <div class="ai-kw" id="ai-kw-list">
            <span class="ai-chip" onclick="rmKw(this)">IRVE <span style="opacity:.5">×</span></span>
            <span class="ai-chip" onclick="rmKw(this)">Sparklin <span style="opacity:.5">×</span></span>
            <span class="ai-chip" onclick="rmKw(this)">recharge entreprise <span style="opacity:.5">×</span></span>
            <span class="ai-chip" style="background:none;border-color:#C4B5FD;color:#7C3AED;cursor:pointer" onclick="addKw()">+ Ajouter</span>
          </div>
          <div id="gen-wrap" style="display:none">
            <div class="gen-prog"><div class="gen-prog-bar" id="gen-bar" style="width:0%"></div></div>
            <div class="gen-steps-wrap" id="gen-steps"></div>
          </div>
          <button class="btn btn-ai" id="ai-btn" style="width:100%;justify-content:center;margin-top:10px" onclick="runAI()">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="m12 2-3.5 7H3l5.5 4-2 7L12 16l5.5 4-2-7L21 9h-5.5L12 2z"/></svg>
            Générer
          </button>
        </div>
      </div>

      <!-- VERSIONS -->
      <div class="panel-section">
        <div class="panel-label">Historique</div>
        <div class="panel-card">
          <div id="versions-list"></div>
        </div>
      </div>

    </div><!-- end editor-panel -->
  </div><!-- end editor-wrap -->
</div>

<!-- ─── ANALYTICS ────────────────────────────────────────────── -->
<div class="view" id="view-analytics">
  <div class="ph"><div><div class="ph-title">Analytiques</div><div class="ph-sub">30 derniers jours</div></div></div>
  <div class="metrics">
    <div class="metric"><div class="metric-row"><div class="metric-icon" style="background:var(--orange-bg)"><svg width="15" height="15" fill="none" stroke="var(--orange)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div><span class="metric-trend t-up">+18 %</span></div><div class="metric-val">3 284</div><div class="metric-lbl">Vues totales</div></div>
    <div class="metric"><div class="metric-row"><div class="metric-icon" style="background:var(--blue-bg)"><svg width="15" height="15" fill="none" stroke="var(--blue)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><span class="metric-trend t-up">+7 %</span></div><div class="metric-val">1 841</div><div class="metric-lbl">Visiteurs uniques</div></div>
    <div class="metric"><div class="metric-row"><div class="metric-icon" style="background:var(--green-bg)"><svg width="15" height="15" fill="none" stroke="var(--green)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div><span class="metric-trend t-up">+3 %</span></div><div class="metric-val">4 min 12 s</div><div class="metric-lbl">Temps moyen</div></div>
    <div class="metric"><div class="metric-row"><div class="metric-icon" style="background:var(--amber-bg)"><svg width="15" height="15" fill="none" stroke="var(--amber)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg></div><span class="metric-trend t-flat">Stable</span></div><div class="metric-val">62 %</div><div class="metric-lbl">Taux de rebond</div></div>
  </div>
  <div style="display:grid;grid-template-columns:1fr 320px;gap:20px">
    <div class="card"><div class="card-inner">
      <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:16px">Vues par jour · 30 jours</div>
      <div class="chart-container" style="height:180px" id="analytics-chart"></div>
      <div class="chart-labels"><span>1 avr.</span><span>30 avr.</span></div>
    </div></div>
    <div class="card"><div class="card-inner">
      <div style="font-size:13px;font-weight:700;color:var(--text);margin-bottom:14px">Top articles</div>
      <div id="top-arts"></div>
    </div></div>
  </div>
</div>

<!-- ─── SETTINGS ─────────────────────────────────────────────── -->
<div class="view" id="view-settings">
  <div class="ph"><div><div class="ph-title">Paramètres</div><div class="ph-sub">Configuration du blog Sparklin</div></div></div>
  <div class="card"><div class="card-inner">
        <div style="font-size:14px;font-weight:700;margin-bottom:16px" id="settings-lang-title">Langues</div>
        <div style="font-size:12px;color:var(--text3);margin-bottom:12px" id="settings-lang-desc">Définissez les marchés géographiques actifs pour le blog Sparklin.</div>
        <div style="display:flex;flex-direction:column;gap:8px" id="settings-lang-list">
          <div class="setting-row"><div class="setting-info"><div class="setting-label">🇫🇷 Français — sparklin.io/fr/blog/</div><div class="setting-desc">Marché principal — France</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
          <div class="setting-row"><div class="setting-info"><div class="setting-label">🇬🇧 English — sparklin.io/en/blog/</div><div class="setting-desc">International — English-speaking markets</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
          <div class="setting-row"><div class="setting-info"><div class="setting-label">🇩🇪 Deutsch — sparklin.io/de/blog/</div><div class="setting-desc">Marché Allemagne · Autriche · Suisse</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
          <div class="setting-row"><div class="setting-info"><div class="setting-label">🇪🇸 Español — sparklin.io/es/blog/</div><div class="setting-desc">Marché Espagne · Amérique latine</div></div><label class="toggle"><input type="checkbox"><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
          <div class="setting-row"><div class="setting-info"><div class="setting-label">🇹🇭 ภาษาไทย — sparklin.io/th/blog/</div><div class="setting-desc">Marché Thaïlande</div></div><label class="toggle"><input type="checkbox"><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
          <div class="setting-row"><div class="setting-info"><div class="setting-label">🇲🇾 Melayu — sparklin.io/ms/blog/</div><div class="setting-desc">Marché Malaisie</div></div><label class="toggle"><input type="checkbox"><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
          <div class="setting-row"><div class="setting-info"><div class="setting-label">🇮🇩 Indonesia — sparklin.io/id/blog/</div><div class="setting-desc">Marché Indonésie</div></div><label class="toggle"><input type="checkbox"><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
        </div>
      </div></div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start">
    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card"><div class="card-inner">
        <div style="font-size:14px;font-weight:700;margin-bottom:16px">Général</div>
        <div class="field-row" style="margin-bottom:12px"><label>Nom du blog</label><input type="text" value="Blog IRVE Sparklin"/></div>
        <div class="field-row" style="margin-bottom:12px"><label>URL de base</label><input type="text" id="base-url-input" value=""/></div>
        <div class="field-row" style="margin-bottom:12px"><label>Auteur par défaut</label><input type="text" value="Équipe Sparklin"/></div>
        <div class="field-row"><label>Méta description globale</label><textarea rows="2">Solutions de recharge IRVE connectées pour entreprises, collectivités et hôtels.</textarea></div>
      </div></div>
      <div class="card"><div class="card-inner">
        <div style="font-size:14px;font-weight:700;margin-bottom:16px">SEO & Réseaux sociaux</div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Sitemap automatique</div><div class="setting-desc">Régénère le sitemap.xml à chaque publication</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Open Graph images</div><div class="setting-desc">Génère une image OG par article</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Partage LinkedIn automatique</div><div class="setting-desc">Publie un extrait à la publication</div></div><label class="toggle"><input type="checkbox"><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
      </div></div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
      <div class="card"><div class="card-inner">
        <div style="font-size:14px;font-weight:700;margin-bottom:16px">Éditeur</div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Sauvegarde automatique</div><div class="setting-desc">Toutes les 30 secondes</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Aperçu temps réel</div><div class="setting-desc">Prévisualisation OG synchronisée</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Mode focus par défaut</div><div class="setting-desc">Masque la barre latérale en édition</div></div><label class="toggle"><input type="checkbox"><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Compte de mots</div><div class="setting-desc">Affiche les statistiques de lecture</div></div><label class="toggle"><input type="checkbox" checked><div class="toggle-track"></div><div class="toggle-thumb"></div></label></div>
      </div></div>
      <div class="card"><div class="card-inner">
        <div style="font-size:14px;font-weight:700;margin-bottom:16px">Intégrations</div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Google Analytics</div><div class="setting-desc" id="ga-status" style="color:var(--green)">Connecté · UA-XXXXXX</div></div><button class="btn btn-secondary btn-sm">Configurer</button></div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">Netlify Forms</div><div class="setting-desc" style="color:var(--green)">Actif · 3 formulaires</div></div><button class="btn btn-secondary btn-sm">Gérer</button></div>
        <div class="setting-row"><div class="setting-info"><div class="setting-label">API IA (Anthropic)</div><div class="setting-desc" style="color:var(--text3)">Non configurée — ajoutez votre clé</div></div><button class="btn btn-primary btn-sm">Connecter</button></div>
      </div></div>
      <div class="card"><div class="card-inner">
        <div style="font-size:14px;font-weight:700;margin-bottom:4px">Danger zone</div>
        <div style="font-size:12px;color:var(--text3);margin-bottom:14px">Ces actions sont irréversibles</div>
        <div style="display:flex;flex-direction:column;gap:8px">
          <button class="btn btn-danger btn-sm" style="justify-content:flex-start">Supprimer tous les brouillons</button>
          <button class="btn btn-danger btn-sm" style="justify-content:flex-start">Réinitialiser les analytiques</button>
        </div>
      </div></div>
    </div>
  </div>
</div>

</main>
</div></div>

<!-- ══ TOAST CONTAINER ════════════════════════════════════════ -->
<div class="toast" id="toast-container"></div>

<!-- ══ LINK MODAL ═════════════════════════════════════════════ -->
<div class="modal-bg" id="link-modal">
  <div class="modal">
    <div class="modal-title">Insérer un lien</div>
    <div class="modal-sub">Entrez l'URL de destination</div>
    <div class="field-row"><label>Texte du lien</label><input type="text" id="link-text" placeholder="Texte affiché" style="margin-bottom:8px"/></div>
    <div class="field-row"><label>URL</label><input type="url" id="link-url" placeholder="https://…"/></div>
    <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:16px">
      <button class="btn btn-secondary" onclick="closeLinkModal()">Annuler</button>
      <button class="btn btn-primary" onclick="confirmLink()">Insérer</button>
    </div>
  </div>
</div>

<script>
/* ═══════════════════════════════════════════════════════════
   DATA
═══════════════════════════════════════════════════════════ */
var ARTICLES=[
  {id:1,lang:'fr',title:'Load balancing IRVE : comment Sparklin déploie 140 bornes sans renforcement EDF',slug:'load-balancing-irve-sparklin',cat:'technique',status:'published',date:'24 avr. 2026',views:1240,readTime:'6 min',words:1180},
  {id:2,lang:'fr',title:'Réglementation LOM 2025 : obligations des entreprises de plus de 20 salariés',slug:'reglementation-lom-2025',cat:'reglementation',status:'published',date:'18 avr. 2026',views:873,readTime:'8 min',words:1620},
  {id:3,lang:'fr',title:'Remboursement URSSAF recharge domicile : le guide complet pour les RH',slug:'remboursement-urssaf-recharge-domicile',cat:'finance',status:'published',date:'10 avr. 2026',views:654,readTime:'7 min',words:1380},
  {id:4,lang:'fr',title:'GIREVE et l\'interopérabilité OCPP : fonctionnement et enjeux pour votre parc',slug:'gireve-interoperabilite-ocpp',cat:'technique',status:'published',date:'2 avr. 2026',views:517,readTime:'5 min',words:970},
  {id:5,lang:'fr',title:'Choisir entre une borne 3,7 kW et 22 kW : guide décisionnel complet',slug:'choisir-borne-37-22kw',cat:'technique',status:'draft',date:'Hier',views:0,readTime:'—',words:420},
  {id:6,lang:'de',title:'Réglementation IRVE 2025 : nouvelles obligations et calendrier détaillé',slug:'reglementation-irve-2025',cat:'reglementation',status:'draft',date:'Il y a 2j',views:0,readTime:'—',words:230},
  {id:7,lang:'en',title:'Guide URSSAF 2025 : nouveaux plafonds de remboursement',slug:'guide-urssaf-2025',cat:'finance',status:'scheduled',date:'1er mai 2026',views:0,readTime:'~6 min',words:0},
];
var CAT={technique:{label:'Technique',color:'#1D4ED8',bg:'#EFF6FF'},reglementation:{label:'Réglementation',color:'#7C3AED',bg:'#F5F3FF'},finance:{label:'Finance',color:'#15803D',bg:'#F0FDF4'},produit:{label:'Produit',color:'#E8563A',bg:'#FFF7F5'},cas:{label:'Cas client',color:'#B45309',bg:'#FFFBEB'},news:{label:'Actualité',color:'#0891B2',bg:'#ECFEFF'}};
var WEEK=[180,240,195,320,290,410,380];
var MONTH=[60,80,45,120,90,140,100,160,130,180,200,160,190,220,180,250,210,240,280,260,300,280,310,290,330,300,350,380,410,380];
var currentFilter='all';
var currentSort='date';
var selectedIds=new Set();
var aiMode='full';
var aiTone='expert';
var aiRunning=false;
var wordGoal=600;
var versions=[];
var autosaveTimer;
var currentEditId=null;
var pubTab='now';
var savedRange=null;

/* ═══════════════════════════════════════════════════════════
   NAVIGATION
═══════════════════════════════════════════════════════════ */
function showView(v){
  document.querySelectorAll('.view').forEach(el=>el.classList.remove('active'));
  document.querySelectorAll('.sb-item').forEach(el=>el.classList.remove('active'));
  document.getElementById('view-'+v).classList.add('active');
  var nav=document.getElementById('nav-'+v);
  if(nav)nav.classList.add('active');
  if(v==='dashboard'){renderDash();}
  if(v==='articles'){renderArts(currentFilter);}
  if(v==='analytics'){renderAnalytics();}
  window.scrollTo(0,0);
}
function openEditor(id){
  if(id){
    var a=ARTICLES.find(x=>x.id===id);
    if(a){
      document.getElementById('ed-title').value=a.title;
      document.getElementById('ed-slug').value=a.slug;
      document.getElementById('pub-slug-preview').textContent=a.slug;
      document.getElementById('ed-cat').value=a.cat;
      document.getElementById('pub-status').className='status '+(a.status==='published'?'s-published':a.status==='scheduled'?'s-scheduled':'s-draft');
      document.getElementById('pub-status').textContent=a.status==='published'?'Publié':a.status==='scheduled'?'Planifié':'Brouillon';
      currentEditId=id;
    }
  } else {
    document.getElementById('ed-title').value='';
    document.getElementById('ed-slug').value='';
    document.getElementById('rte-editor').innerHTML='';
    document.getElementById('ed-meta').value='';
    document.getElementById('ed-focus-kw').value='';
    document.getElementById('pub-slug-preview').textContent='…';
    document.getElementById('pub-status').className='status s-draft';
    document.getElementById('pub-status').textContent='Brouillon';
    currentEditId=null;
  }
  versions=[];renderVersions();
  updateSeo();updateWords();updateWordGoal(wordGoal);
  document.getElementById('og-title').textContent=document.getElementById('ed-title').value||'Titre de votre article';
  document.getElementById('og-desc').textContent=document.getElementById('ed-meta').value||'La méta description apparaîtra ici…';
  showView('editor');
  setTimeout(()=>document.getElementById('ed-title').focus(),100);
}

/* ═══════════════════════════════════════════════════════════
   DASHBOARD
═══════════════════════════════════════════════════════════ */
function renderDash(){
  var today=new Date();
  document.getElementById('dash-date').textContent='Mise à jour : '+today.toLocaleDateString('fr-FR',{weekday:'long',day:'numeric',month:'long'});
  
  // Mini table
  var tbody=document.getElementById('dash-tbody');
  tbody.innerHTML=ARTICLES.slice(0,5).map((a,i)=>`
    <tr class="art-row" onclick="openEditor(${a.id})">
      <td><input type="checkbox" class="art-check" onclick="e=>e.stopPropagation()"/></td>
      <td><div class="art-title-cell">${a.title}</div><div class="art-slug">/blog/${a.slug}</div></td>
      <td><span class="status s-${a.status}">${statusLabel(a.status)}</span></td>
      <td style="font-size:12px;font-family:var(--fm);color:var(--text2);text-align:right">${a.views?a.views.toLocaleString('fr-FR'):'—'}</td>
      <td><div class="art-actions"><button class="btn btn-ghost btn-icon btn-sm link-icon-btn" onclick="event.stopPropagation();openEditor(${a.id})" title="Modifier"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"/></svg></button></div></td>
    </tr>`).join('');

  renderChart('dash-chart',WEEK,7);

  // Activity
  var acts=[
    {ico:'✓',color:CAT.technique.bg,tcolor:CAT.technique.color,text:'<strong>Load balancing IRVE</strong> publié — 1 240 vues',time:'2h'},
    {ico:'✎',color:'var(--bg2)',tcolor:'var(--text3)',text:'Brouillon <strong>Réglementation 2025</strong> mis à jour',time:'Hier'},
    {ico:'⏰',color:CAT.finance.bg,tcolor:CAT.finance.color,text:'<strong>Guide URSSAF 2025</strong> planifié pour le 1er mai',time:'2j'},
    {ico:'👁',color:CAT.reglementation.bg,tcolor:CAT.reglementation.color,text:'<strong>LOM 2025</strong> atteint 800 vues ce mois',time:'3j'},
  ];
  document.getElementById('dash-activity').innerHTML=acts.map(a=>`
    <div class="act-item">
      <div class="act-ico" style="background:${a.color};color:${a.tcolor};font-size:12px">${a.ico}</div>
      <div class="act-text">${a.text}</div>
      <div class="act-time">${a.time}</div>
    </div>`).join('');
}

/* ═══════════════════════════════════════════════════════════
   ARTICLES LIST
═══════════════════════════════════════════════════════════ */
function statusLabel(s){return{published:'Publié',draft:'Brouillon',scheduled:'Planifié',review:'Révision'}[s]||s;}
function catPill(c){var d=CAT[c];if(!d)return'';return`<span class="cat-pill" style="background:${d.bg};color:${d.color}">${d.label}</span>`;}

function renderArts(filter){
  var list=filter==='all'?ARTICLES:ARTICLES.filter(a=>a.status===filter);
  if(currentSort==='views') list=[...list].sort((a,b)=>b.views-a.views);
  document.getElementById('art-count-sub').textContent=list.length+' article'+(list.length>1?'s':'');
  var tbody=document.getElementById('art-tbody');
  if(!list.length){tbody.innerHTML='<tr><td colspan="7" style="text-align:center;padding:40px 20px;color:var(--text3);font-size:13px">Aucun article dans cette catégorie</td></tr>';return;}
  tbody.innerHTML=list.map((a,i)=>`
    <tr class="art-row" onclick="openEditor(${a.id})" data-id="${a.id}">
      <td><input type="checkbox" class="art-check" onclick="e=>{e.stopPropagation();toggleSel(${a.id},e.target.checked)}" ${selectedIds.has(a.id)?'checked':''}/></td>
      <td>
        <div class="art-title-cell">${a.title}</div>
        <div class="art-slug">/${a.lang||'fr'}/blog/${a.slug}${a.words?' · '+a.words+' mots':''}</div>
      </td>
      <td>${catPill(a.cat)}</td>
      <td><span class="status s-${a.status}">${statusLabel(a.status)}</span></td>
      <td style="font-size:11px;font-family:var(--fm);color:var(--text3)">${a.date}</td>
      <td style="font-size:12px;font-family:var(--fm);color:var(--text2);text-align:right">${a.views?a.views.toLocaleString('fr-FR'):'—'}</td>
      <td>
        <div class="art-actions">
          <button class="btn btn-ghost btn-icon btn-sm link-icon-btn" title="Modifier" onclick="event.stopPropagation();openEditor(${a.id})"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"/></svg></button>
          <button class="btn btn-ghost btn-icon btn-sm link-icon-btn" title="Dupliquer" onclick="event.stopPropagation();duplicateArt(${a.id})" style="color:var(--blue)"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
          <button class="btn btn-ghost btn-icon btn-sm link-icon-btn" title="Supprimer" onclick="event.stopPropagation();deleteArt(${a.id})" style="color:var(--red)"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
        </div>
      </td>
    </tr>`).join('');
}

function setFilter(f,btn){
  currentFilter=f;
  document.querySelectorAll('.fpill').forEach(p=>p.classList.remove('active'));
  btn.classList.add('active');
  selectedIds.clear();updateBatchBtn();
  renderArts(f);
}
function sortBy(k){currentSort=k;renderArts(currentFilter);}

function toggleSel(id,checked){
  if(checked)selectedIds.add(id);else selectedIds.delete(id);
  updateBatchBtn();
}
function toggleAll(cb){
  var list=currentFilter==='all'?ARTICLES:ARTICLES.filter(a=>a.status===currentFilter);
  list.forEach(a=>{if(cb.checked)selectedIds.add(a.id);else selectedIds.delete(a.id);});
  updateBatchBtn();renderArts(currentFilter);
}
function updateBatchBtn(){
  var btn=document.getElementById('batch-btn');
  var n=selectedIds.size;
  btn.style.display=n?'inline-flex':'none';
  document.getElementById('batch-count').textContent=n;
}
function batchAction(){
  if(!selectedIds.size)return;
  if(confirm('Supprimer les '+selectedIds.size+' articles sélectionnés ?')){
    ARTICLES=ARTICLES.filter(a=>!selectedIds.has(a.id));
    selectedIds.clear();updateBatchBtn();renderArts(currentFilter);
    updateMetrics();toast('Articles supprimés','red');
  }
}
function duplicateArt(id){
  var a=ARTICLES.find(x=>x.id===id);if(!a)return;
  var copy={...a,id:Date.now(),title:'Copie de '+a.title,slug:a.slug+'-copie',status:'draft',date:'À l\'instant',views:0};
  ARTICLES.unshift(copy);renderArts(currentFilter);updateMetrics();
  toast('Article dupliqué en brouillon','green');
}
function deleteArt(id){
  var a=ARTICLES.find(x=>x.id===id);if(!a)return;
  if(confirm('Supprimer "'+a.title.substring(0,50)+'…" ?')){
    ARTICLES=ARTICLES.filter(x=>x.id!==id);
    renderArts(currentFilter);updateMetrics();toast('Article supprimé','red');
  }
}
function updateMetrics(){
  document.getElementById('m-pub').textContent=ARTICLES.filter(a=>a.status==='published').length;
  document.getElementById('m-draft').textContent=ARTICLES.filter(a=>a.status==='draft').length;
  document.getElementById('m-sched').textContent=ARTICLES.filter(a=>a.status==='scheduled').length;
  document.getElementById('sb-count').textContent=ARTICLES.length;
}

/* ═══════════════════════════════════════════════════════════
   EDITOR
═══════════════════════════════════════════════════════════ */
function fmt(cmd,val){document.execCommand(cmd,false,val||null);}
function handleShortcuts(e){
  if(e.ctrlKey||e.metaKey){
    if(e.key==='b'){e.preventDefault();fmt('bold');}
    if(e.key==='i'){e.preventDefault();fmt('italic');}
    if(e.key==='u'){e.preventDefault();fmt('underline');}
    if(e.key==='s'){e.preventDefault();saveDraft();}
  }
}
function wrapCode(){
  var sel=window.getSelection();
  if(!sel.rangeCount)return;
  var range=sel.getRangeAt(0);
  var code=document.createElement('code');
  range.surroundContents(code);
}
function insertHR(){
  document.execCommand('insertHTML',false,'<hr/>');
}
function insertLink(){
  savedRange=null;
  var sel=window.getSelection();
  if(sel&&sel.rangeCount>0) savedRange=sel.getRangeAt(0).cloneRange();
  var selText=sel?sel.toString():'';
  document.getElementById('link-text').value=selText;
  document.getElementById('link-url').value='https://';
  document.getElementById('link-modal').classList.add('open');
  setTimeout(()=>document.getElementById('link-url').focus(),100);
}
function closeLinkModal(){document.getElementById('link-modal').classList.remove('open');}
function confirmLink(){
  var url=document.getElementById('link-url').value;
  var txt=document.getElementById('link-text').value;
  if(!url){closeLinkModal();return;}
  var ed=document.getElementById('rte-editor');
  ed.focus();
  if(savedRange){
    var sel=window.getSelection();
    sel.removeAllRanges();sel.addRange(savedRange);
  }
  document.execCommand('insertHTML',false,`<a href="${url}">${txt||url}</a>`);
  closeLinkModal();
}
function insertImage(input){
  var f=input.files[0];if(!f)return;
  var r=new FileReader();
  r.onload=e=>{document.execCommand('insertImage',false,e.target.result);};
  r.readAsDataURL(f);input.value='';
}
function onTitle(v){
  var slug=v.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').slice(0,80);
  document.getElementById('ed-slug').value=slug;
  document.getElementById('pub-slug-preview').textContent=slug||'…';
  document.getElementById('og-title').textContent=v||'Titre de votre article';
  updateSeo();scheduleAutosave();
}
function onSlug(v){
  document.getElementById('pub-slug-preview').textContent=v||'…';
}
function onMeta(v){
  var n=v.length;
  var hint=document.getElementById('meta-hint');
  hint.textContent=n+' / 160';
  hint.className='char-hint'+(n>=120&&n<=160?' ch-ok':n>160?' ch-warn':'');
  document.getElementById('og-desc').textContent=v||'La méta description apparaîtra ici…';
  updateSeo();scheduleAutosave();
}
function onEditorInput(){updateWords();updateSeo();scheduleAutosave();}

function updateWords(){
  var body=document.getElementById('rte-editor').innerText||'';
  var words=body.trim().split(/\s+/).filter(w=>w.length>0).length;
  var mins=Math.max(1,Math.ceil(words/200));
  document.getElementById('rte-words').textContent=words.toLocaleString('fr-FR')+' mot'+(words>1?'s':'')+' · '+mins+' min';
  // Reading level (rough)
  var level=words<100?'Début':words<400?'Court':words<800?'Moyen':words<1500?'Long':'Complet';
  document.getElementById('rte-level').textContent=level;
  // Goal
  updateWordGoal(wordGoal,words);
}
function updateWordGoal(goal,current){
  wordGoal=parseInt(goal)||0;
  var words=current!=null?current:(() => {
    var t=document.getElementById('rte-editor').innerText||'';
    return t.trim().split(/\s+/).filter(w=>w.length>0).length;
  })();
  if(!wordGoal){document.getElementById('word-goal-bar').style.width='0%';document.getElementById('rte-goal-text').textContent='';return;}
  var pct=Math.min(100,Math.round(words/wordGoal*100));
  var bar=document.getElementById('word-goal-bar');
  bar.style.width=pct+'%';
  bar.style.background=pct>=100?'var(--green)':pct>60?'var(--orange)':'var(--border2)';
  document.getElementById('rte-goal-text').textContent='Objectif '+pct+'%';
}

/* ── SEO ── */
function updateSeo(){
  var title=document.getElementById('ed-title').value||'';
  var meta=document.getElementById('ed-meta').value||'';
  var slug=document.getElementById('ed-slug').value||'';
  var kw=document.getElementById('ed-focus-kw').value.toLowerCase()||'';
  var body=(document.getElementById('rte-editor').innerText||'');
  var words=body.trim().split(/\s+/).filter(w=>w.length>0).length;

  var checks=[
    {ok:title.length>=50&&title.length<=70,label:'Titre 50–70 caractères ('+title.length+')'},
    {ok:meta.length>=120&&meta.length<=160,label:'Méta 120–160 caractères ('+meta.length+')'},
    {ok:slug.length>3,label:'Slug défini'},
    {ok:words>=500,label:'Contenu ≥ 500 mots ('+words+')'},
    {ok:kw&&title.toLowerCase().includes(kw),label:'Mot-clé dans le titre'},
    {ok:kw&&body.toLowerCase().includes(kw),label:'Mot-clé dans le corps'},
  ];
  var score=Math.round(checks.filter(c=>c.ok).length/checks.length*100);
  var circ=document.getElementById('seo-circle');
  var circumference=125.6;
  circ.style.strokeDashoffset=circumference-(circumference*score/100);
  circ.style.stroke=score>=80?'var(--green)':score>=50?'var(--orange)':'var(--red)';
  document.getElementById('seo-score-val').textContent=score;
  document.getElementById('seo-label').textContent=score>=80?'Excellent':score>=50?'Moyen':'À améliorer';

  var icons={true:'<svg width="12" height="12" fill="none" stroke="var(--green)" stroke-width="2.2" stroke-linecap="round" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>',false:'<svg width="12" height="12" fill="none" stroke="var(--text3)" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>'};
  document.getElementById('seo-checks').innerHTML=checks.map(c=>`
    <div class="seo-item ${c.ok?'seo-ok':'seo-fail'}">${icons[c.ok]}<span>${c.label}</span></div>`).join('');
}

/* ── AUTOSAVE ── */
function scheduleAutosave(){
  clearTimeout(autosaveTimer);
  var el=document.getElementById('editor-save-text');
  if(el)el.textContent='Modification…';
  autosaveTimer=setTimeout(()=>{
    saveVersion();
    if(el)el.textContent='Sauvegardé';
  },2000);
}
function saveVersion(){
  var body=document.getElementById('rte-editor').innerHTML;
  var words=(document.getElementById('rte-editor').innerText||'').trim().split(/\s+/).filter(w=>w.length>0).length;
  if(!words&&!versions.length)return;
  var now=new Date();
  versions.unshift({time:now.toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit'}),words:words,html:body});
  if(versions.length>5)versions.pop();
  renderVersions();
}
function renderVersions(){
  var el=document.getElementById('versions-list');
  if(!versions.length){el.innerHTML='<div style="font-size:12px;color:var(--text3);text-align:center;padding:8px">Aucune version sauvegardée</div>';return;}
  el.innerHTML=versions.map((v,i)=>`
    <div class="version-item">
      <div><div class="ver-time">${v.time}</div><div class="ver-words">${v.words} mots</div></div>
      <button class="ver-restore" onclick="restoreVersion(${i})">Restaurer</button>
    </div>`).join('');
}
function restoreVersion(i){
  document.getElementById('rte-editor').innerHTML=versions[i].html;
  updateWords();updateSeo();
  toast('Version restaurée','green');
}
function copySlug(){
  var slug=document.getElementById('ed-slug').value;
  navigator.clipboard.writeText((window.SK_SITE_URL||window.location.origin)+'/blog/'+slug).then(()=>toast('URL copiée','green'));
}

/* ── PUBLISH ── */
function setPubTab(t,btn){
  pubTab=t;
  document.querySelectorAll('.pub-tab').forEach(el=>el.classList.remove('active'));
  if(btn)btn.classList.add('active');
  var sdt=document.getElementById('schedule-dt');
  sdt.classList.toggle('show',t==='sched');
  var mainBtn=document.getElementById('pub-main-btn');
  mainBtn.textContent=t==='sched'?'Planifier':t==='draft'?'Sauver':'Publier';
  mainBtn.onclick=t==='sched'?schedulePub:t==='draft'?saveDraft:publishNow;
}
function publishNow(){
  var t=document.getElementById('ed-title').value;
  if(!t.trim()){toast('Titre requis pour publier','red');return;}
  document.getElementById('pub-status').className='status s-published';
  document.getElementById('pub-status').textContent='Publié';
  if(currentEditId){var a=ARTICLES.find(x=>x.id===currentEditId);if(a)a.status='published';}
  else{
    ARTICLES.unshift({id:Date.now(),title:t,slug:document.getElementById('ed-slug').value,cat:document.getElementById('ed-cat').value,status:'published',date:'À l\'instant',views:0,readTime:'—',words:0});
  }
  updateMetrics();
  toast('Article publié sur le blog !','green');
}
function saveDraft(){
  var t=document.getElementById('ed-title').value;
  if(!t.trim()){toast('Ajoutez un titre','amber');return;}
  document.getElementById('pub-status').className='status s-draft';
  document.getElementById('pub-status').textContent='Brouillon';
  saveVersion();
  toast('Brouillon sauvegardé','green');
}
function schedulePub(){
  var dt=document.getElementById('schedule-dt').value;
  if(!dt){toast('Choisissez une date de publication','amber');return;}
  document.getElementById('pub-status').className='status s-scheduled';
  document.getElementById('pub-status').textContent='Planifié';
  var d=new Date(dt);
  toast('Planifié pour le '+d.toLocaleDateString('fr-FR',{day:'2-digit',month:'long'}),'green');
}
function onScheduleChange(v){
  if(v){var d=new Date(v);document.getElementById('pub-main-btn').textContent='Planifier';}
}

/* ── IMAGE DROP ── */
function handleDrop(e){
  e.preventDefault();
  document.getElementById('img-drop-zone').classList.remove('over');
  var f=e.dataTransfer.files[0];if(!f)return;
  handleFile(f);
}
function handleCover(input){handleFile(input.files[0]);}
function handleFile(f){
  if(!f||!f.type.startsWith('image/')){toast('Fichier image requis','red');return;}
  var r=new FileReader();
  r.onload=e=>{
    document.getElementById('img-drop-content').innerHTML=`<img src="${e.target.result}" style="width:100%;height:80px;object-fit:cover;border-radius:6px;display:block"/>
    <button style="margin-top:6px;font-size:11px;color:var(--red);background:none;border:none;cursor:pointer;font-family:var(--fb)" onclick="removeImg()">Supprimer l'image</button>`;
  };
  r.readAsDataURL(f);
}
function removeImg(){
  document.getElementById('img-drop-content').innerHTML=`<div class="img-drop-icon" style="display:flex;justify-content:center"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg></div><p>Cliquer ou glisser une image</p><small>JPG, PNG, WebP · Max 5 MB</small>`;
}

/* ── FOCUS MODE ── */
var focusMode=false;
function toggleFocusMode(){
  focusMode=!focusMode;
  document.body.classList.toggle('focus-mode',focusMode);
  if(focusMode)toast('Mode focus activé — Appuyez Échap pour quitter','green');
}
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&focusMode){focusMode=false;document.body.classList.remove('focus-mode');}});

/* ── AI ── */
var AI_SAMPLES={
  full:`<h2>Introduction : la recharge électrique professionnelle en 2026</h2><p>Le déploiement de bornes de recharge pour véhicules électriques représente aujourd'hui un enjeu stratégique majeur pour les entreprises françaises. Qu'il s'agisse de répondre aux obligations légales issues de la Loi d'Orientation des Mobilités (LOM) ou d'optimiser les coûts liés à la mobilité professionnelle, la question du dimensionnement et de la supervision de l'infrastructure IRVE est devenue centrale.</p><h2>Pourquoi le load balancing change tout</h2><p>Le load balancing dynamique permet de répartir intelligemment la puissance électrique disponible entre l'ensemble des bornes d'un site. Contrairement aux installations traditionnelles où chaque borne consomme indépendamment sa puissance nominale, un système intégré comme Spark Pilot garantit que la puissance totale consommée ne dépasse jamais le plafond EDF.</p><blockquote>«&thinsp;Avec Sparklin, nous avons déployé 140 bornes sur notre campus sans renégocier une seule fois notre abonnement EDF. Le load balancing s'occupe de tout.&thinsp;»<br>— Responsable Mobilité, Groupe SFR</blockquote><h2>Les avantages concrets</h2><ul><li>Aucun renforcement du raccordement électrique dans 90 % des cas</li><li>Réduction des coûts d'installation de 30 à 50 %</li><li>Scalabilité totale : ajoutez des bornes sans revoir l'architecture</li><li>Conformité LOM garantie avec traçabilité complète</li></ul><h2>Conclusion</h2><p>Avec les bons outils — notamment une plateforme de supervision intégrant le load balancing natif — la recharge professionnelle devient simple à déployer, à gérer et à rentabiliser. Sparklin accompagne plus de 50 entreprises dans cette transition.</p>`,
  intro:`<p>La recharge de véhicules électriques en entreprise est souvent perçue comme complexe et coûteuse. Pourtant, avec les bons outils, c'est l'un des leviers les plus efficaces pour réduire l'empreinte carbone de votre flotte tout en offrant un service apprécié de vos collaborateurs. Découvrez comment le load balancing dynamique de Sparklin transforme cette infrastructure en atout compétitif.</p>`,
  outline:`<h2>1. Contexte réglementaire et obligations LOM</h2><p><em>À compléter : seuils d'obligation selon la taille du parking, calendrier 2025–2027…</em></p><h2>2. Les enjeux techniques du déploiement IRVE</h2><p><em>À compléter : puissance disponible, PDL, raccordement Enedis…</em></p><h2>3. La solution load balancing de Sparklin</h2><p><em>À compléter : fonctionnement, Spark Pilot, résultats mesurés…</em></p><h2>4. ROI et cas client</h2><p><em>À compléter : étude de cas SFR, calcul économique type…</em></p><h2>5. Comment démarrer</h2><p><em>À compléter : étapes de déploiement, audit gratuit, contact…</em></p>`,
  meta:`<p><em>Suggestion de titre :</em><br><strong>Load balancing IRVE 2026 : déployez 140 bornes sans renforcement EDF — Guide Sparklin</strong></p><p><em>Méta description (147 caractères) :</em><br>Découvrez comment Sparklin déploie jusqu'à 140 bornes IRVE sans renforcement EDF grâce au load balancing dynamique. Guide complet pour entreprises.</p>`
};
var GEN_STEPS=['Analyse du titre et des mots-clés…','Structuration du plan éditorial…','Rédaction du contenu principal…','Optimisation SEO et lisibilité…','Relecture et finalisation…'];

function setAiMode(m){
  aiMode=m;
  ['full','intro','outline','meta'].forEach(k=>document.getElementById('ai-'+k).classList.toggle('sel',k===m));
}
function setTone(btn){
  aiTone=btn.dataset.t;
  document.querySelectorAll('.ai-tone').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
}
function rmKw(el){el.remove();}
function addKw(){
  var kw=prompt('Nouveau mot-clé :');
  if(!kw)return;
  var list=document.getElementById('ai-kw-list');
  var chip=document.createElement('span');
  chip.className='ai-chip';chip.onclick=()=>chip.remove();
  chip.innerHTML=kw+' <span style="opacity:.5">×</span>';
  list.insertBefore(chip,list.lastElementChild);
}

function runAI(){
  if(aiRunning)return;
  var title=document.getElementById('ed-title').value;
  if(!title.trim()){toast('Ajoutez un titre pour générer','amber');return;}
  aiRunning=true;
  var btn=document.getElementById('ai-btn');
  btn.disabled=true;
  btn.innerHTML='<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg> Génération…';
  var wrap=document.getElementById('gen-wrap');
  var stepsEl=document.getElementById('gen-steps');
  wrap.style.display='block';
  stepsEl.innerHTML=GEN_STEPS.map((s,i)=>`<div class="gen-step" id="gs${i}">${s}</div>`).join('');
  var bar=document.getElementById('gen-bar');

  var delays=[0,700,1500,2500,3600];
  var pcts=[15,35,60,82,100];
  GEN_STEPS.forEach((_,i)=>{
    setTimeout(()=>{
      if(i>0){var prev=document.getElementById('gs'+(i-1));if(prev){prev.classList.remove('on');prev.classList.add('done');}}
      var cur=document.getElementById('gs'+i);if(cur)cur.classList.add('on');
      bar.style.width=pcts[i]+'%';
    },delays[i]);
  });

  setTimeout(()=>{
    var lastStep=document.getElementById('gs'+(GEN_STEPS.length-1));
    if(lastStep){lastStep.classList.remove('on');lastStep.classList.add('done');}
    var content=AI_SAMPLES[aiMode]||AI_SAMPLES.full;
    document.getElementById('rte-editor').innerHTML=content;
    if(aiMode==='meta'){
      var metaMatch=content.match(/[\d]+ caractères\):<\/em><br>(.*?)<\/p>/);
      if(!document.getElementById('ed-meta').value)
        document.getElementById('ed-meta').value='Découvrez comment Sparklin déploie jusqu\'à 140 bornes IRVE sans renforcement EDF grâce au load balancing dynamique. Guide complet pour entreprises.';
      onMeta(document.getElementById('ed-meta').value);
    }
    updateWords();updateSeo();saveVersion();
    setTimeout(()=>{
      wrap.style.display='none';
      bar.style.width='0%';
      GEN_STEPS.forEach((_,i)=>{var el=document.getElementById('gs'+i);if(el){el.classList.remove('on','done');}});
      aiRunning=false;btn.disabled=false;
      btn.innerHTML='<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="m12 2-3.5 7H3l5.5 4-2 7L12 16l5.5 4-2-7L21 9h-5.5L12 2z"/></svg> Regénérer';
      toast('Contenu généré avec succès !','green');
    },300);
  },4200);
}

/* ═══════════════════════════════════════════════════════════
   ANALYTICS
═══════════════════════════════════════════════════════════ */
function renderAnalytics(){
  renderChart('analytics-chart',MONTH,30);
  var sorted=[...ARTICLES].filter(a=>a.views>0).sort((a,b)=>b.views-a.views);
  var max=sorted[0]?.views||1;
  document.getElementById('top-arts').innerHTML=sorted.map((a,i)=>`
    <div class="top-art">
      <div class="top-art-rank">${i+1}</div>
      <div class="top-art-info">
        <div class="top-art-title">${a.title}</div>
        <div class="top-art-bar-wrap"><div class="top-art-bar" style="width:${Math.round(a.views/max*100)}%"></div></div>
      </div>
      <div class="top-art-views">${a.views.toLocaleString('fr-FR')}</div>
    </div>`).join('');
}

function renderChart(id,data,n){
  var el=document.getElementById(id);if(!el)return;
  var max=Math.max(...data);
  el.innerHTML='<div class="chart-bars">'+data.map((v,i)=>{
    var pct=Math.round(v/max*95)+5;
    var isLast=i===data.length-1;
    var opacity=isLast?1:0.4+(i/data.length)*0.4;
    return`<div class="chart-bar" style="height:${pct}%;background:var(--orange);opacity:${opacity};border-radius:3px 3px 0 0" title="${v} vues"></div>`;
  }).join('')+'</div>';
}

/* ═══════════════════════════════════════════════════════════
   SEARCH
═══════════════════════════════════════════════════════════ */
function liveSearch(q){
  if(!q.trim())return;
  var results=ARTICLES.filter(a=>a.title.toLowerCase().includes(q.toLowerCase()));
  if(document.getElementById('view-articles').classList.contains('active')){
    var tbody=document.getElementById('art-tbody');
    if(!tbody)return;
    tbody.innerHTML=results.map((a,i)=>`
      <tr class="art-row" onclick="openEditor(${a.id})">
        <td><input type="checkbox" class="art-check"/></td>
        <td><div class="art-title-cell">${highlight(a.title,q)}</div><div class="art-slug">/blog/${a.slug}</div></td>
        <td>${catPill(a.cat)}</td>
        <td><span class="status s-${a.status}">${statusLabel(a.status)}</span></td>
        <td style="font-size:11px;font-family:var(--fm);color:var(--text3)">${a.date}</td>
        <td style="font-size:12px;font-family:var(--fm);color:var(--text2);text-align:right">${a.views?a.views.toLocaleString('fr-FR'):'—'}</td>
        <td></td>
      </tr>`).join('');
  }
}
function highlight(text,q){
  var re=new RegExp('('+q.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+')','gi');
  return text.replace(re,'<mark style="background:var(--orange-bg2);border-radius:2px">$1</mark>');
}
document.addEventListener('keydown',e=>{
  if((e.ctrlKey||e.metaKey)&&e.key==='k'){e.preventDefault();document.getElementById('search-input').focus();}
  if(e.key==='Escape'&&document.activeElement===document.getElementById('search-input')){document.getElementById('search-input').value='';document.getElementById('search-input').blur();}
  if(e.key==='Escape'&&document.getElementById('link-modal').classList.contains('open'))closeLinkModal();
});
document.getElementById('link-modal').addEventListener('click',e=>{if(e.target===document.getElementById('link-modal'))closeLinkModal();});

/* ═══════════════════════════════════════════════════════════
   TOAST
═══════════════════════════════════════════════════════════ */
function toast(msg,type){
  var c=document.getElementById('toast-container');
  var colors={green:'var(--green)',red:'var(--red)',amber:'var(--amber)',blue:'var(--blue)'};
  var item=document.createElement('div');
  item.className='toast-item';
  item.innerHTML=`<div class="toast-dot2" style="background:${colors[type]||colors.green}"></div>${msg}`;
  c.appendChild(item);
  setTimeout(()=>{item.style.animation='none';item.style.opacity='0';item.style.transform='translateY(8px)';item.style.transition='all .3s';setTimeout(()=>item.remove(),300);},2800);
}

/* ═══════════════════════════════════════════════════════════
   INIT
═══════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded',()=>{
  renderDash();
  updateMetrics();
  updateWordGoal(wordGoal);
  updateSeo();
  renderVersions();
  // Autosave loop
  setInterval(()=>{
    if(document.getElementById('view-editor').classList.contains('active')){
      var t=document.getElementById('ed-title').value;
      if(t.trim())saveVersion();
    }
  },30000);
});



/* ═══════════════════════════════════════════════════════════
   LANGUAGE SYSTEM
═══════════════════════════════════════════════════════════ */

/* ── Language metadata ── */
var LANGS = {
  fr: { flag:'🇫🇷', label:'Français',    code:'fr', geo:'France',      path:'fr',   rtl:false },
  en: { flag:'🇬🇧', label:'English',     code:'en', geo:'International', path:'en', rtl:false },
  de: { flag:'🇩🇪', label:'Deutsch',     code:'de', geo:'Allemagne',    path:'de',   rtl:false },
  es: { flag:'🇪🇸', label:'Español',     code:'es', geo:'Espagne',      path:'es',   rtl:false },
  th: { flag:'🇹🇭', label:'ภาษาไทย',    code:'th', geo:'Thaïlande',    path:'th',   rtl:false },
  ms: { flag:'🇲🇾', label:'Melayu',      code:'ms', geo:'Malaisie',     path:'ms',   rtl:false },
  id: { flag:'🇮🇩', label:'Indonesia',   code:'id', geo:'Indonésie',    path:'id',   rtl:false }
};

/* ── UI strings (interface translations) ── */
var UI_STRINGS = {
  fr: {
    newArticle:     'Nouvel article',
    dashboard:      'Tableau de bord',
    articles:       'Articles',
    analytics:      'Statistiques',
    settings:       'Paramètres',
    publish:        'Publier',
    draft:          'Brouillon',
    schedule:       'Planifier',
    now:            'Maintenant',
    published:      'Publié',
    scheduled:      'Planifié',
    status:         'Statut',
    visibility:     'Visibilité',
    author:         'Auteur',
    public:         'Public',
    category:       'Catégorie',
    tags:           'Tags',
    coverImage:     'Image de couverture',
    wordGoal:       'Objectif de mots',
    metaDesc:       'Méta description',
    focusKw:        'Mot-clé principal',
    seoScore:       'Score SEO',
    pending:        'En attente',
    artLang:        "Langue de l'article",
    publishedOn:    'Publié sur',
    interfaceLang:  "Langue de l'interface",
    titlePlaceholder: "Titre de l'article…",
    metaPlaceholder:  '120–160 caractères…',
    kwPlaceholder:    'Ex : recharge IRVE entreprise',
    searchPlaceholder:'Rechercher…',
    selectCat:        '— Sélectionner —',
    tagsPlaceholder:  'IRVE, LOM, load balancing…',
    savedLabel:       'Sauvegardé',
    saving:           'Sauvegarde…',
    wordStats:        (w,m) => `${w} mot${w>1?'s':''} · ${m} min`,
    langInfo:         (path, geo) => `Publié sur sparklin.io/${path}/blog/ · Zone : ${geo}`,
    views:            'Vues',
    date:             'Date',
    langue:           'Langue',
    titre:            'Titre',
  },
  en: {
    newArticle:     'New article',
    dashboard:      'Dashboard',
    articles:       'Articles',
    analytics:      'Analytics',
    settings:       'Settings',
    publish:        'Publish',
    draft:          'Draft',
    schedule:       'Schedule',
    now:            'Now',
    published:      'Published',
    scheduled:      'Scheduled',
    status:         'Status',
    visibility:     'Visibility',
    author:         'Author',
    public:         'Public',
    category:       'Category',
    tags:           'Tags',
    coverImage:     'Cover image',
    wordGoal:       'Word goal',
    metaDesc:       'Meta description',
    focusKw:        'Focus keyword',
    seoScore:       'SEO Score',
    pending:        'Pending',
    artLang:        'Article language',
    publishedOn:    'Published on',
    interfaceLang:  'Interface language',
    titlePlaceholder: 'Article title…',
    metaPlaceholder:  '120–160 characters…',
    kwPlaceholder:    'e.g. EV charging enterprise',
    searchPlaceholder:'Search…',
    selectCat:        '— Select —',
    tagsPlaceholder:  'IRVE, LOM, load balancing…',
    savedLabel:       'Saved',
    saving:           'Saving…',
    wordStats:        (w,m) => `${w} word${w>1?'s':''} · ${m} min`,
    langInfo:         (path, geo) => `Published on sparklin.io/${path}/blog/ · Region: ${geo}`,
    views:            'Views',
    date:             'Date',
    langue:           'Language',
    titre:            'Title',
  },
  de: {
    newArticle:     'Neuer Artikel',
    dashboard:      'Dashboard',
    articles:       'Artikel',
    analytics:      'Statistiken',
    settings:       'Einstellungen',
    publish:        'Veröffentlichen',
    draft:          'Entwurf',
    schedule:       'Planen',
    now:            'Jetzt',
    published:      'Veröffentlicht',
    scheduled:      'Geplant',
    status:         'Status',
    visibility:     'Sichtbarkeit',
    author:         'Autor',
    public:         'Öffentlich',
    category:       'Kategorie',
    tags:           'Tags',
    coverImage:     'Titelbild',
    wordGoal:       'Wortziel',
    metaDesc:       'Meta-Beschreibung',
    focusKw:        'Fokus-Keyword',
    seoScore:       'SEO-Score',
    pending:        'Ausstehend',
    artLang:        'Artikelsprache',
    publishedOn:    'Veröffentlicht auf',
    interfaceLang:  'Oberflächensprache',
    titlePlaceholder: 'Artikeltitel…',
    metaPlaceholder:  '120–160 Zeichen…',
    kwPlaceholder:    'z.B. EVSE Unternehmen',
    searchPlaceholder:'Suchen…',
    selectCat:        '— Auswählen —',
    tagsPlaceholder:  'IRVE, LOM, Lastmanagement…',
    savedLabel:       'Gespeichert',
    saving:           'Speichern…',
    wordStats:        (w,m) => `${w} Wort${w>1?'e':''} · ${m} Min.`,
    langInfo:         (path, geo) => `Auf sparklin.io/${path}/blog/ · Region: ${geo}`,
    views:            'Aufrufe',
    date:             'Datum',
    langue:           'Sprache',
    titre:            'Titel',
  },
  es: {
    newArticle:     'Nuevo artículo',
    dashboard:      'Panel de control',
    articles:       'Artículos',
    analytics:      'Estadísticas',
    settings:       'Configuración',
    publish:        'Publicar',
    draft:          'Borrador',
    schedule:       'Programar',
    now:            'Ahora',
    published:      'Publicado',
    scheduled:      'Programado',
    status:         'Estado',
    visibility:     'Visibilidad',
    author:         'Autor',
    public:         'Público',
    category:       'Categoría',
    tags:           'Etiquetas',
    coverImage:     'Imagen de portada',
    wordGoal:       'Objetivo de palabras',
    metaDesc:       'Meta descripción',
    focusKw:        'Palabra clave',
    seoScore:       'Puntuación SEO',
    pending:        'Pendiente',
    artLang:        'Idioma del artículo',
    publishedOn:    'Publicado en',
    interfaceLang:  'Idioma de la interfaz',
    titlePlaceholder: 'Título del artículo…',
    metaPlaceholder:  '120–160 caracteres…',
    kwPlaceholder:    'Ej: recarga IRVE empresa',
    searchPlaceholder:'Buscar…',
    selectCat:        '— Seleccionar —',
    tagsPlaceholder:  'IRVE, LOM, load balancing…',
    savedLabel:       'Guardado',
    saving:           'Guardando…',
    wordStats:        (w,m) => `${w} palabra${w>1?'s':''} · ${m} min`,
    langInfo:         (path, geo) => `Publicado en sparklin.io/${path}/blog/ · Región: ${geo}`,
    views:            'Vistas',
    date:             'Fecha',
    langue:           'Idioma',
    titre:            'Título',
  }
};
/* Add minimal stubs for TH/MS/ID (UI stays in English if not FR/EN/DE/ES) */
['th','ms','id'].forEach(lc => {
  UI_STRINGS[lc] = Object.assign({}, UI_STRINGS.en, {
    artLang: UI_STRINGS.en.artLang,
    interfaceLang: UI_STRINGS.en.interfaceLang
  });
});

/* ── State ── */
var currentUiLang = localStorage.getItem('sk_admin_ui_lang') || 'fr';
var currentArtLang = 'fr';

/* ── UI (interface) language ── */
function setUiLang(lang, btn) {
  currentUiLang = lang;
  localStorage.setItem('sk_admin_ui_lang', lang);

  /* update topbar buttons */
  document.querySelectorAll('[data-ui-lang]').forEach(b => b.classList.toggle('active', b.dataset.uiLang === lang));

  applyUiLang();
}

function applyUiLang() {
  var s = UI_STRINGS[currentUiLang] || UI_STRINGS.fr;

  /* sidebar */
  var sbNew = document.querySelector('.sb-new-btn');
  if (sbNew) sbNew.childNodes[sbNew.childNodes.length-1].textContent = ' ' + s.newArticle;
  setText('nav-dashboard',   s.dashboard);
  setText('nav-articles',    s.articles);
  setText('nav-analytics',   s.analytics);
  setText('nav-settings',    s.settings);

  /* editor panel labels */
  setLabel('lbl-article-lang', s.artLang);
  setLabel('lbl-pub-label',    'Publication');  /* keep for now */

  /* publication panel buttons */
  var pubBtns = document.querySelectorAll('.pub-tab');
  if (pubBtns.length >= 3) {
    pubBtns[0].textContent = s.now;
    pubBtns[1].textContent = s.schedule;
    pubBtns[2].textContent = s.draft;
  }
  var pubMainBtn = document.getElementById('pub-main-btn');
  if (pubMainBtn) { pubMainBtn.childNodes[pubMainBtn.childNodes.length-1].textContent = ' ' + s.publish; }

  /* pub meta labels */
  var pubRows = document.querySelectorAll('.pub-row .pub-row-l');
  if (pubRows[0]) pubRows[0].textContent = s.status;
  if (pubRows[1]) pubRows[1].textContent = s.visibility;
  if (pubRows[2]) pubRows[2].textContent = s.author;
  var pubRowsR = document.querySelectorAll('.pub-row .pub-row-r');
  if (pubRowsR[0]) pubRowsR[0].textContent = s.public;

  /* right panel section labels */
  var panelLabels = document.querySelectorAll('.panel-label');
  if (panelLabels[1]) panelLabels[1].textContent = 'SEO';
  if (panelLabels[2]) panelLabels[2].textContent = s.artLang;
  if (panelLabels[3]) panelLabels[3].textContent = (currentUiLang==='fr'?'Paramètres article':
                                                      currentUiLang==='de'?'Artikeleinstellungen':
                                                      currentUiLang==='es'?'Parámetros del artículo':
                                                      'Article settings');

  /* editor placeholders */
  var edTitle = document.getElementById('ed-title');
  if (edTitle) edTitle.placeholder = s.titlePlaceholder;
  var edMeta = document.getElementById('ed-meta');
  if (edMeta) edMeta.placeholder = s.metaPlaceholder;
  var edKw = document.getElementById('ed-focus-kw');
  if (edKw) edKw.placeholder = s.kwPlaceholder;
  var searchInput = document.getElementById('search-input');
  if (searchInput) searchInput.placeholder = s.searchPlaceholder;

  /* rte placeholder */
  var rte = document.getElementById('rte-editor');
  if (rte) rte.dataset.ph = (currentUiLang==='en'?'Start writing your article…':
                               currentUiLang==='de'?'Beginnen Sie Ihren Artikel zu schreiben…':
                               currentUiLang==='es'?'Comience a escribir su artículo…':
                               'Commencez à écrire votre article…');

  /* saved indicator */
  var saveText = document.querySelector('.tb-save');
  if (saveText && saveText.childNodes[1]) saveText.childNodes[1].textContent = ' ' + s.savedLabel;

  /* article lang info update */
  updateArtLangInfo();

  /* re-render articles list if visible */
  if (document.getElementById('view-articles').classList.contains('active')) {
    renderArts(currentFilter);
  }
}

function setText(id, text) {
  var el = document.getElementById(id);
  if (!el) return;
  /* preserve child elements (badge spans etc.), only update text nodes */
  Array.from(el.childNodes).forEach(n => {
    if (n.nodeType === 3) n.textContent = ' ' + text + ' ';
  });
}
function setLabel(id, text) {
  var el = document.getElementById(id);
  if (el) el.textContent = text;
}

/* ── Article language ── */
function setArtLang(lang, btn) {
  currentArtLang = lang;

  /* update buttons */
  document.querySelectorAll('[data-art-lang]').forEach(b => b.classList.toggle('selected', b.dataset.artLang === lang));

  /* update URL preview */
  var prefix = document.getElementById('pub-url-lang-prefix');
  if (prefix) prefix.textContent = lang;

  /* update slug base display */
  var slugBase = document.querySelector('.editor-slug-base');
  if (slugBase) slugBase.textContent = 'sparklin.io/' + lang + '/blog/';

  /* update info hint */
  updateArtLangInfo();

  /* scroll article lang hint into view */
  var info = document.getElementById('art-lang-info');
  if (info) info.scrollIntoView({behavior:'smooth', block:'nearest'});
}

function updateArtLangInfo() {
  var info = document.getElementById('art-lang-info');
  if (!info) return;
  var l = LANGS[currentArtLang] || LANGS.fr;
  var s = UI_STRINGS[currentUiLang] || UI_STRINGS.fr;
  var path = l.path;
  info.innerHTML = l.flag + ' ' + l.label + ' &nbsp;·&nbsp; <strong>sparklin.io/' + path + '/blog/</strong> &nbsp;·&nbsp; Zone : ' + l.geo;
}

/* ── Language badge for article list ── */
function langBadge(langCode) {
  var l = LANGS[langCode] || LANGS.fr;
  return '<span class="lang-badge"><span class="lbf">' + l.flag + '</span>' + l.code.toUpperCase() + '</span>';
}

/* ── Patch openEditor to restore article lang ── */
var _origOpenEditor = openEditor;
openEditor = function(id) {
  _origOpenEditor(id);
  if (id) {
    var a = ARTICLES.find(x => x.id === id);
    if (a && a.lang) {
      /* set lang buttons */
      document.querySelectorAll('[data-art-lang]').forEach(b => b.classList.toggle('selected', b.dataset.artLang === (a.lang || 'fr')));
      currentArtLang = a.lang || 'fr';
    }
  } else {
    /* new article: reset to FR */
    currentArtLang = 'fr';
    document.querySelectorAll('[data-art-lang]').forEach(b => b.classList.toggle('selected', b.dataset.artLang === 'fr'));
  }
  /* update URL and slug base */
  var prefix = document.getElementById('pub-url-lang-prefix');
  if (prefix) prefix.textContent = currentArtLang;
  var slugBase = document.querySelector('.editor-slug-base');
  if (slugBase) slugBase.textContent = 'sparklin.io/' + currentArtLang + '/blog/';
  updateArtLangInfo();
};

/* ── Patch renderArts to show language column ── */
var _origRenderArts = renderArts;
renderArts = function(filter) {
  /* add lang to ARTICLES that don't have it (default FR) */
  ARTICLES.forEach(a => { if (!a.lang) a.lang = 'fr'; });
  _origRenderArts(filter);
  /* inject lang badges into each row */
  document.querySelectorAll('#art-tbody tr.art-row').forEach(row => {
    var id = parseInt(row.dataset.id);
    var art = ARTICLES.find(x => x.id === id);
    if (!art) return;
    /* find the category cell (3rd td after checkboxes) and insert lang badge before it */
    var tds = row.querySelectorAll('td');
    if (tds.length >= 3) {
      /* insert lang td after title td */
      var langTd = row.querySelector('td.lang-col');
      if (!langTd) {
        langTd = document.createElement('td');
        langTd.className = 'lang-col';
        langTd.style.width = '60px';
        tds[1].after(langTd);
      }
      langTd.innerHTML = langBadge(art.lang || 'fr');
    }
  });
};

/* ── Patch statusLabel for i18n ── */
var _origStatusLabel = statusLabel;
statusLabel = function(s) {
  var ui = UI_STRINGS[currentUiLang] || UI_STRINGS.fr;
  return {published: ui.published, draft: ui.draft, scheduled: ui.scheduled}[s] || _origStatusLabel(s);
};

/* ── Init on load ── */
document.addEventListener('DOMContentLoaded', function() {
  /* restore UI lang from localStorage */
  var savedUiLang = localStorage.getItem('sk_admin_ui_lang') || 'fr';
  currentUiLang = savedUiLang;
  document.querySelectorAll('[data-ui-lang]').forEach(b => b.classList.toggle('active', b.dataset.uiLang === savedUiLang));
  applyUiLang();

  /* set default art lang */
  updateArtLangInfo();
  setArtLang('fr', null);
});




/* ── Language filter for articles list ── */
var currentLangFilter = null;
function setLangFilter(lang, btn) {
  /* deactivate status filter pills */
  document.querySelectorAll('.fpill[data-f^="lang-"]').forEach(b => b.classList.remove('active'));
  var statusPills = document.querySelectorAll('.fpill:not([data-f^="lang-"])');

  if (currentLangFilter === lang) {
    /* toggle off — show all */
    currentLangFilter = null;
    btn.classList.remove('active');
    statusPills.forEach(b => { if(b.dataset.f==='all') b.classList.add('active'); });
    renderArts(currentFilter);
  } else {
    currentLangFilter = lang;
    btn.classList.add('active');
    statusPills.forEach(b => b.classList.remove('active'));
    renderArtsFiltered();
  }
}

function renderArtsFiltered() {
  var s = UI_STRINGS[currentUiLang] || UI_STRINGS.fr;
  ARTICLES.forEach(a => { if (!a.lang) a.lang = 'fr'; });
  var list = currentLangFilter
    ? ARTICLES.filter(a => a.lang === currentLangFilter)
    : ARTICLES;
  if (currentSort === 'views') list = [...list].sort((a,b) => b.views - a.views);

  document.getElementById('art-count-sub').textContent = list.length + ' article' + (list.length>1?'s':'');
  var tbody = document.getElementById('art-tbody');
  if (!list.length) {
    tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:40px 20px;color:var(--text3);font-size:13px">Aucun article dans cette langue</td></tr>';
    return;
  }
  tbody.innerHTML = list.map((a) => `
    <tr class="art-row" onclick="openEditor(${a.id})" data-id="${a.id}">
      <td><input type="checkbox" class="art-check" onclick="e=>{e.stopPropagation();toggleSel(${a.id},e.target.checked)}" ${selectedIds.has(a.id)?'checked':''}/></td>
      <td>
        <div class="art-title-cell">${a.title}</div>
        <div class="art-slug">/${a.lang}/blog/${a.slug}${a.words?' · '+a.words+' mots':''}</div>
      </td>
      <td>${langBadge(a.lang||'fr')}</td>
      <td>${catPill(a.cat)}</td>
      <td><span class="status s-${a.status}">${statusLabel(a.status)}</span></td>
      <td style="font-size:11px;font-family:var(--fm);color:var(--text3)">${a.date}</td>
      <td style="font-size:12px;font-family:var(--fm);color:var(--text2);text-align:right">${a.views?a.views.toLocaleString('fr-FR'):'—'}</td>
      <td>
        <div class="art-actions">
          <button class="btn btn-ghost btn-icon btn-sm link-icon-btn" title="Modifier" onclick="event.stopPropagation();openEditor(${a.id})"><svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4L18.5 2.5z"/></svg></button>
        </div>
      </td>
    </tr>`).join('');
  
  /* re-inject lang badges (redundant here but consistent) */
  document.querySelectorAll('#art-tbody tr.art-row').forEach(row => {
    var id = parseInt(row.dataset.id);
    var art = ARTICLES.find(x => x.id === id);
    if (art) {
      var langTd = row.querySelector('td.lang-col');
      if (langTd) langTd.innerHTML = langBadge(art.lang||'fr');
    }
  });
}
</script>

<script>
// Set base URL dynamically to avoid hardcoding the production URL
(function() {
  var base = window.location.origin + '/blog/';
  var inp = document.getElementById('base-url-input');
  if (inp) inp.value = base;
  window.SK_SITE_URL = window.location.origin;
})();
</script>
</body>
</html>
