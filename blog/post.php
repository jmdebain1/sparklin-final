<?php
require_once __DIR__ . '/../includes/env.php';
require_once __DIR__ . '/../includes/supabase.php';
require_once __DIR__ . '/../includes/i18n.php';
require_once __DIR__ . '/../includes/content.php';
loadEnv(__DIR__ . '/../.env');
$lang = initI18n();

$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/', '', (string)$_GET['slug']) : '';
$post = $slug ? getPostBySlug($slug) : null;

if (!$post || $post['status'] !== 'published') {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}

$MOIS_FR = [1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
function sk_format_date_fr(?string $iso, array $mois): string {
    if (!$iso) return '';
    $ts = strtotime($iso);
    if (!$ts) return '';
    return date('j', $ts) . ' ' . $mois[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
$publishedLabel = sk_format_date_fr($post['published_at'] ?? null, $MOIS_FR);
$authorInitial  = mb_strtoupper(mb_substr($post['author'] ?: 'M', 0, 1));
$pageTitle      = $post['meta_title'] ?: $post['title'];
$pageDesc       = $post['meta_desc'] ?: $post['excerpt'];
$heroImage      = $post['hero_image']
    ? (preg_match('#^https?://#', $post['hero_image']) ? $post['hero_image'] : 'https://sparklin.io' . $post['hero_image'])
    : null;
?>
<!DOCTYPE html>
<html lang="<?= lang() ?>">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDesc) ?>"/>
  <link rel="icon" href="/favicon.ico" sizes="any"/>
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/images/favicon-32.png"/>
  <link rel="icon" type="image/png" sizes="16x16" href="/assets/images/favicon-16.png"/>
  <link rel="apple-touch-icon" sizes="180x180" href="/assets/images/apple-touch-icon.png"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Wix+Madefor+Display:wght@400;500;600;700;800&family=Wix+Madefor+Text:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/assets/css/style.css">
  <?php if (!empty($post['keywords'])): ?>
  <meta name="keywords" content="<?= htmlspecialchars($post['keywords']) ?>"/>
  <?php endif; ?>
  <script type="application/ld+json">
  <?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post['title'],
    'description' => $pageDesc,
    'image' => $heroImage ?: 'https://sparklin.io/assets/images/apple-touch-icon.png',
    'datePublished' => $post['published_at'],
    'author' => ['@type' => 'Person', 'name' => $post['author']],
    'publisher' => ['@type' => 'Organization', 'name' => 'Sparklin', 'url' => 'https://sparklin.io'],
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => 'https://sparklin.io/blog/' . $post['slug'] . '/'],
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
  </script>
  <?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/includes/seo.php';
  sk_seo_head(array_filter([
    'title' => $pageTitle,
    'desc' => $pageDesc,
    'type' => 'article',
    'image' => $heroImage,
    'imageAlt' => $post['hero_image_alt'],
  ]));
  ?>
</head>
<body>
<?php require __DIR__ . '/../includes/header.php'; ?>

<main style="padding-top:64px;">
<?php if ($heroImage): ?>
<div style="height:340px;background:#1a1a2e;display:flex;align-items:flex-end;padding:0;position:relative;overflow:hidden;">
  <img src="<?= htmlspecialchars($post['hero_image']) ?>" alt="<?= htmlspecialchars($post['hero_image_alt'] ?: $post['title']) ?>" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.55;"/>
  <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(26,26,46,.2) 0%,rgba(26,26,46,.85) 100%);"></div>
</div>
<?php endif; ?>
<article style="padding:60px clamp(20px,5vw,80px);">
  <div class="sk-wrap sk-wrap--narrow blog-body">
    <?php if (!empty($post['category'])): ?>
    <div style="font-size:12px;font-weight:600;color:var(--text-light);letter-spacing:.08em;text-transform:uppercase;margin-bottom:16px;"><?= htmlspecialchars($post['category']) ?></div>
    <?php endif; ?>

    <h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,3vw,2.6rem);font-weight:800;color:var(--dark);line-height:1.2;margin-bottom:20px;"><?= htmlspecialchars($post['title']) ?></h1>
    <div class="blog-byline"><div class="blog-byline-avatar"><?= htmlspecialchars($authorInitial) ?></div><span>Par <strong><?= htmlspecialchars($post['author']) ?></strong></span><?php if ($publishedLabel): ?><span class="blog-byline-dot"></span><span><?= htmlspecialchars($publishedLabel) ?></span><?php endif; ?></div>
    <?php if (!empty($post['excerpt'])): ?>
    <div style="margin-bottom:32px;"><?= htmlspecialchars($post['excerpt']) ?></div>
    <?php endif; ?>

    <?= $post['body_html'] ?>

    <div style="margin-top:48px;padding-top:32px;border-top:1px solid var(--border);">
      <a href="/blog/" style="font-size:14px;color:var(--text-mid);text-decoration:none;font-weight:500;"><?= tr('blog.back') ?></a>
    </div>
  </div>
</article>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
