<!doctype html>
<html lang="vi">
<head>
  <?php
    $defaultTitle = site_config('default_meta_title', site_config('name'));
    $defaultDescription = site_config('default_meta_description', '');
    $defaultOgImage = site_config('default_og_image') ?: frontend_asset('assets/images/hero-industrial.webp');
    $resolvedTitle = $pageTitle ?? $defaultTitle;
    $resolvedDescription = $pageDescription ?? $defaultDescription;
    $resolvedCanonical = $canonicalUrl ?? request()->url();
    $resolvedImage = site_asset_url($ogImage ?? $defaultOgImage);
    $resolvedRobots = $robots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $resolvedLanguage = $language ?? site_config('default_language', 'vi');
    $resolvedLocale = $resolvedLanguage === 'vi' ? 'vi_VN' : $resolvedLanguage;
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  <meta name="language" content="<?= e($resolvedLanguage) ?>">
  <meta name="author" content="<?= e(site_config('name')) ?>">
  <title><?= e($resolvedTitle) ?></title>
  <meta name="description" content="<?= e($resolvedDescription) ?>">
  <meta name="keywords" content="<?= e($pageKeywords ?? site_config('default_meta_keywords', '')) ?>">
  <meta name="robots" content="<?= e($resolvedRobots) ?>">
  <link rel="canonical" href="<?= e($resolvedCanonical) ?>">
  <?php foreach (($alternateLinks ?? []) as $alternateLanguage => $alternateUrl): ?>
  <link rel="alternate" hreflang="<?= e($alternateLanguage) ?>" href="<?= e($alternateUrl) ?>">
  <?php endforeach; ?>
  <meta property="og:locale" content="<?= e($resolvedLocale) ?>">
  <meta property="og:type" content="<?= e($ogType ?? 'website') ?>">
  <meta property="og:title" content="<?= e($resolvedTitle) ?>">
  <meta property="og:description" content="<?= e($resolvedDescription) ?>">
  <meta property="og:url" content="<?= e($resolvedCanonical) ?>">
  <meta property="og:site_name" content="<?= e(site_config('name')) ?>">
  <meta property="og:image" content="<?= e($resolvedImage) ?>">
  <meta property="og:image:alt" content="<?= e($ogImageAlt ?? $resolvedTitle) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($twitterTitle ?? $resolvedTitle) ?>">
  <meta name="twitter:description" content="<?= e($twitterDescription ?? $resolvedDescription) ?>">
  <meta name="twitter:image" content="<?= e($twitterImage ?? $resolvedImage) ?>">
  <?= view('partials.frontend.favicon')->render() ?>
  <?php if (site_config('google_site_verification')): ?>
  <meta name="google-site-verification" content="<?= e(site_config('google_site_verification')) ?>">
  <?php endif; ?>

  <?php if (!empty($jsonLd)): ?>
  <script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
  <?php endif; ?>

  <?= tracking_code('head') ?>

  <?php
    $styleEntries = array_map(
        static fn (string $style): string => 'resources/css/frontend/' . basename($style),
        $extraStyles ?? [],
    );
  ?>
  <?= app(\Illuminate\Foundation\Vite::class)(array_merge([
      'resources/css/app.css',
      'resources/css/frontend/style.css',
      'resources/css/frontend/navigation.css',
      'resources/js/app-user.js',
  ], $styleEntries)) ?>
</head>
<body class="<?= e($bodyClass ?? '') ?>">
<?= tracking_code('body_start') ?>


