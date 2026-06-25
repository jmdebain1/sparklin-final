# Sparklin — Project guide & change log

Static PHP marketing site for **Sparklin** (smart EV-charging / IRVE), served by PHP's built-in
server in dev. Pages are individual `index.php` files per route (no framework, no shared layout
include — the nav/footer "chrome" is copy-pasted into each page).

Run locally: `php -S localhost:8080 -t .` (or `preview_start` with `.claude/launch.json`).

## Architecture notes

- **i18n** — 7 languages (`fr en de es th ms id`) via Supabase table `public.sparklin_i18n`
  (project `szzkzqszaorszytizgzf`). Columns: `key`, `category` (generated), one per language
  (Indonesian column is **`id_lang`**, not `id`).
  - `includes/i18n.php`: `t('key')` → HTML-escaped, `tr('key')` → raw (use for values containing
    HTML like `<strong>`/`<em>`). Language from `?lang=` → `sk_lang` cookie → `fr` fallback.
  - `includes/supabase.php`: fetches translations from the Supabase REST API with a 5-min file
    cache in the temp dir; **paginates** (PostgREST caps at 1000 rows and the table exceeds that).
  - `assets/js/app.js` does **no** client-side i18n — `data-i18n` attributes are dormant markers;
    all swapping is server-side via `t()`/`tr()`.
  - Translations were pushed via the Supabase REST upsert API (`Prefer: resolution=merge-duplicates`).
- **Styles** — single active stylesheet `assets/css/style.css` (the old `home.css`/`main.css` were
  removed). Responsive breakpoints at 900 / 768 / 640 / 600 / 380 px. Many pages also carry
  inline styles and per-page `<style>` blocks.
- **Nav / header** is identical and duplicated across all 22 page files; edits must be applied to
  every page (and the `.claude/worktrees/*` copies are a separate git worktree — ignore them).
- The homepage `#sparklin-interconnect` block is rendered by `assets/js/sparklin-interconnect.js`,
  which **overwrites the element's `style.cssText` at runtime** — don't rely on inline styles there.
- Intentionally left in French (out of i18n scope): footer "chrome", and `<head>` `<title>`/meta
  (SEO).

## Modifications

### 2026-06-25
- **Full body-content translation (7 languages)** of every page: homepage, Spark Pilot, Sparklin
  App, Spark 1 / Plus / Go-E, a-propos, contact, support, evenements, livre-blanc (+ merci), the 5
  `cas/*` case pages, and the blog (index + 4 long-form articles). Translations stored in Supabase
  and wired with `t()`/`tr()`. Fixed several broken/duplicate source strings found along the way
  (duplicate intro paragraphs, empty/cut-off `<strong>` in trust bars, malformed `</p</p>`, typos).
- **Header / nav translation** — translated the desktop mega-nav and mobile drawer across all 22
  pages (reusing existing `nav.*`/`footer.lnk.*` keys where they matched, plus new `nav.*` keys).
- **Language selector** (`includes`/per-page nav + footer): the trigger label was hardcoded `FR`;
  now renders `<?= strtoupper(lang()) ?>` and the active option is highlighted. All 22 pages.
- **Supabase pagination fix** (`includes/supabase.php`) — `fetchTranslations()` now paginates so
  translations aren't silently truncated once the table passed PostgREST's 1000-row cap.
- **Removed deprecated `curl_close()`** in `includes/supabase.php` (no-op since PHP 8.0) that was
  printing PHP deprecation warnings into every rendered page.
- **Hero H1 spacing fix** — heroes that build the H1 from two keys (`h1a` + `<em>h1b</em>`) ran the
  words together (e.g. "uneplateforme"). Added a space before `<em>` on a-propos, app, cas/camping,
  cas/collectivite, cas/hotel, livre-blanc.
- **Mobile responsive audit & fixes** (`assets/css/style.css`):
  - `.intro-image` inline `aspect-ratio`/`min-height` was overriding the mobile rule and causing a
    horizontal overflow — neutralized on mobile.
  - Added a mobile safety-net that collapses inline multi-column content grids (custom heroes,
    value/spec/reg cards, fixed sidebars) to a single column ≤768px, while leaving small icon grids
    (`64px 1fr`, timeline rows) alone.
  - Fixed the evenements "notify me" form overflow (stacks input + button on mobile).
- **Homepage spacing** — added `margin-top:56px` on `.home-net-feats` for more separation below the
  `#sparklin-interconnect` animation (its inline margin is wiped by the interconnect JS).
