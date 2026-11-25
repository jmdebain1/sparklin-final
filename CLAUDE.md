# Sparklin — Project guide & change log

Static PHP marketing site for **Sparklin** (smart EV-charging / IRVE), served by PHP's built-in
server in dev. Pages are individual `index.php` files per route (no framework, no shared layout
include — the nav/footer "chrome" is copy-pasted into each page).

Run locally: `php -S localhost:8080 -t .` (or `preview_start` with `.claude/launch.json`).

## Architecture notes

- **i18n** — 10 languages (`fr en de es th ms id tr vi fil`) via Supabase table `public.sparklin_i18n`
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

### 2026-11-25 (livres blancs sectoriels, pré-prod)
- **Page pré-prod `/livres-blancs/`** (`livres-blancs/index.php`) : noindex, absente de `sitemap.xml` et de la navigation, mais construite par
  `build-static.sh`. 5 guides (restaurants, hôtels, gîtes, golf, collectivités) : mockup + modale de formulaire (e-mail, prénom, établissement).
  PDF dans `assets/livres-blancs/`, mockups dans `assets/images/livres-blancs/` (golf = mockup généré à partir de la couverture du PDF).
- **`send-livre-blanc.mjs`** accepte `guide` : inscription dans la liste Brevo du guide (gîtes 11, golf 9, collectivités 10 ; surcharge
  `BREVO_LIST_LB_<CLE>` ; hôtels et restaurants n'ont **pas** encore de liste), mail via le template Brevo **30** « Livre blanc sectoriel »
  (params NAME, PDF_URL, GUIDE_TITLE, SECTOR, COVER_URL, ITEMS ; surcharge `BREVO_TEMPLATE_LB_SECTOR_ID`), lead Supabase `livre-blanc-<guide>`,
  notification équipe (template 3) avec le nom du guide. Sans `guide` : comportement « Entreprise » inchangé.

### 2026-11-25 (éditeur d'article)
- **Barre d'outils de l'éditeur réparée et fixe.** Les boutons B/I/H2/H3… ne faisaient rien (`onmousedown="e=>{…}"` n'exécute jamais la
  fonction). Ils passent par `data-cmd` + un écouteur délégué (`rteRun`), avec états actifs (`selectionchange`), bascule H2/H3/citation/code,
  Ctrl+K lien, retirer le lien, effacer la mise en forme. `.editor-wrap` a une hauteur fixe et `.editor-body` défile en interne, donc
  `.editor-topbar` (sticky) reste toujours visible (y compris en mode focus).
- **Coller un article entier** (`onEditorPaste`) : HTML Word/Google Docs/web nettoyé (`sanitizePastedHtml`), Markdown ou texte brut converti,
  un H1 de tête devient le titre. Puis `autoFillSeo()` remplit extrait, méta description, mot-clé : heuristique locale immédiate, puis
  affinage IA via la fonction Netlify `admin-seo-fields.mjs`. Les champs retouchés à la main (`seoDirty`) ne sont pas écrasés ; bouton
  « ✨ Générer auto » pour forcer.
- **Aperçu** : modale pleine hauteur, bascule Ordinateur/Tablette/Mobile, aperçu Google (titre + méta), échappement du HTML, liens bloqués.

### 2026-11-25
- **Admin in English also translates editorial content.** Elements marked `data-tx` (article titles in lists, event and
  press cards) are translated on demand in EN mode: English titles already stored in `post_translations` are preloaded
  (no AI call); anything else goes through the Netlify function `admin-translate.mjs` (Anthropic API, admin session
  required) and is cached in `localStorage` (`sk_admin_tx_en`). The editor always edits the French source (a note says
  so); in EN mode the Preview shows the English translation (stored one if the text is unchanged, otherwise translated
  on the fly, e.g. drafts).
- **Admin blog fully translatable to English.** `assets/js/admin-i18n.js` (loaded by `admin-blog/index.php` and
  `login.php`) translates every visible text node and placeholder/title/aria-label on the fly when the UI language
  is EN (also text injected later by JS: toasts, lists, confirm/prompt) and restores French on FR. Add new admin
  strings to `EXACT`/`PATTERNS` there (the `UI_STRINGS` fr/en pairs are fed in automatically). Article content
  (`#rte-editor`, titles) is never translated. Dates/numbers follow `uiLocale()`.
- **Article languages are now a multi-select** (editor right panel; FR always on, default = all 10). Stored in
  `posts.languages text[]` (NULL = all languages). `admin-posts.mjs` validates it; `translatePost.mjs` translates only
  the selected languages and only when the French source changed (`post_translations.source_hash`);
  `includes/content.php` (`sk_post_in_lang`) hides an article from the blog listing / translation overlay of
  languages that are not selected.

### 2026-11-18
- **Blog translated in every language.** Articles live in Supabase `posts` (French only), so only the page
  chrome used to translate. New table `post_translations (post_id, lang, title, excerpt, category, meta_*,
  hero_image_alt, read_time, body_html)`; `includes/content.php` overlays the current language and falls back to
  French field by field. New articles are translated automatically: `admin-posts.mjs` calls the background
  function `admin-posts-translate.mjs` (Anthropic API, `lib/translatePost.mjs`) on publish/update, which then
  triggers a rebuild.
- **3 new languages: Turkish `tr`, Vietnamese `vi`, Filipino `fil`.** Columns `tr`/`vi`/`fil` added to
  `sparklin_i18n` (all UI keys in use translated); added to `includes/i18n.php`, `supabase.php`, `seo.php`
  (hreflang + og:locale), `scripts/build-static.sh`, `sitemap.xml`, every language menu, and the 2-letter
  `?lang=` regexes (now 2-3 letters). Date formatting per language: `sk_format_date()` in `includes/i18n.php`.
  Still French by design: footer chrome, cookie banner, legal pages.

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
