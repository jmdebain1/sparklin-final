/* Sparklin — app.js (PHP+Supabase architecture, no client-side i18n) */
(function() {
  'use strict';

  /* ── NAV MEGA MENU ── */
  function toggleMega(id) {
    var item = document.getElementById(id);
    if (!item) return;
    var panel = item.querySelector('.nav-mega-panel');
    if (!panel) return;
    var isOpen = panel.style.display === 'flex';
    closeMega();
    if (!isOpen) {
      panel.style.display = 'flex';
      item.classList.add('open');
    }
  }

  function closeMega() {
    document.querySelectorAll('.nav-mega-item').forEach(function(item) {
      var panel = item.querySelector('.nav-mega-panel');
      if (panel) panel.style.display = 'none';
      item.classList.remove('open');
    });
  }

  document.addEventListener('click', function(e) {
    if (!e.target.closest) return;
    if (!e.target.closest('.nav-mega-item') && !e.target.closest('.nav-mega')) closeMega();
  });

  /* ── FAQ ── */
  function toggleFaq(btn) {
    var item = btn.closest('.faq-item');
    if (!item) return;
    var open = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(function(i) { i.classList.remove('open'); });
    if (!open) item.classList.add('open');
  }

  /* ── SCROLL REVEALS ── */
  function initReveals() {
    if (!window.IntersectionObserver) return;
    var sel = '.reveal, .h-reveal, .h-reveal-left, .h-reveal-right';
    var els = document.querySelectorAll(sel);
    if (!els.length) return;
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (!e.isIntersecting) return;
        var sibs = e.target.parentElement
          ? Array.from(e.target.parentElement.querySelectorAll('.reveal,.h-reveal,.h-reveal-left,.h-reveal-right'))
          : [];
        e.target.style.transitionDelay = (sibs.indexOf(e.target) * 0.08) + 's';
        e.target.classList.add('visible');
        obs.unobserve(e.target);
      });
    }, { threshold: 0.1 });
    els.forEach(function(el) { obs.observe(el); });
  }

  /* ── WORD CYCLE (hero) ── */
  function initWordCycle() {
    var el = document.getElementById('hero-word');
    if (!el || el._wc) return;
    el._wc = true;
    /* PHP injects window._wordCycleWords; fallback to FR */
    var words = window._wordCycleWords || ['intelligente.','rentable.','maîtrisée.','connectée.'];
    var i = 0;
    el.style.transition = 'opacity .3s, transform .3s';
    setInterval(function() {
      i = (i + 1) % words.length;
      el.style.opacity = '0'; el.style.transform = 'translateY(-10px)';
      setTimeout(function() {
        el.textContent = words[i];
        el.style.transition = 'none';
        el.style.transform = 'translateY(10px)';
        el.style.opacity = '0';
        setTimeout(function() {
          el.style.transition = 'opacity .4s, transform .4s';
          el.style.opacity = '1';
          el.style.transform = 'translateY(0)';
        }, 20);
      }, 320);
    }, 3200);
  }

  /* ── COUNTERS ── */
  function initCounters() {
    if (!window.IntersectionObserver) return;
    var els = document.querySelectorAll('.count-up');
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (!e.isIntersecting) return;
        var el = e.target, target = parseInt(el.dataset.target, 10), dur = 1600, t0 = performance.now();
        (function tick(now) {
          var p = Math.min((now - t0) / dur, 1);
          el.textContent = Math.round((1 - Math.pow(1 - p, 3)) * target);
          if (p < 1) requestAnimationFrame(tick);
        })(t0);
        obs.unobserve(el);
      });
    }, { threshold: 0.5 });
    els.forEach(function(c) { obs.observe(c); });
  }

  /* ── LANG MENU (UI only — no translation logic) ── */
  function toggleLangMenu(e) {
    e.stopPropagation();
    var menu = document.getElementById('sk-lang-menu');
    var trigger = document.getElementById('sk-lang-trigger');
    var caret = document.getElementById('sk-lang-caret');
    if (!menu) return;
    var isOpen = menu.style.display === 'block';
    closeLangMenu();
    if (!isOpen) {
      menu.style.display = 'block';
      if(trigger) { trigger.classList.add('open'); trigger.setAttribute('aria-expanded','true'); }
      if(caret) caret.style.transform = 'rotate(180deg)';
    }
  }
  function toggleLangMenuFooter(e) {
    e.stopPropagation();
    var menu = document.getElementById('sk-lang-menu-footer');
    var caret = document.getElementById('sk-lang-caret-footer');
    if (!menu) return;
    var isOpen = menu.style.display === 'block';
    closeLangMenu();
    if (!isOpen) {
      menu.style.display = 'block';
      if(caret) caret.style.transform = 'rotate(180deg)';
    }
  }
  function closeLangMenu() {
    ['sk-lang-menu','sk-lang-menu-footer'].forEach(function(id) {
      var el = document.getElementById(id);
      if(el) el.style.display = 'none';
    });
    var t = document.getElementById('sk-lang-trigger');
    if(t){t.classList.remove('open');t.setAttribute('aria-expanded','false');}
    var c1=document.getElementById('sk-lang-caret'), c2=document.getElementById('sk-lang-caret-footer');
    if(c1)c1.style.transform=''; if(c2)c2.style.transform='';
  }
  document.addEventListener('click', function(e) {
    var btn = e.target.closest('button.sk-lang-opt[data-lang]');
    if (btn) {
      var lang = btn.getAttribute('data-lang');
      if (lang) { window.location.href = '?lang=' + lang; return; }
    }
    closeLangMenu();
  });

  /* ── BOOTSTRAP ── */
  document.addEventListener('DOMContentLoaded', function() {
    initReveals();
    if (document.getElementById('hero-word')) {
      initWordCycle();
      initCounters();
    }
    if (document.getElementById('sparklin-interconnect') && typeof initSparklinInterconnect === 'function') {
      setTimeout(initSparklinInterconnect, 200);
    }
  });

  /* Expose for inline onclick */
  window.toggleMega = toggleMega;
  window.closeMega  = closeMega;
  window.toggleFaq  = toggleFaq;
  window.toggleLangMenu = toggleLangMenu;
  window.toggleLangMenuFooter = toggleLangMenuFooter;
  window.closeLangMenu = closeLangMenu;

})();
