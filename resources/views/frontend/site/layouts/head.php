<!doctype html>
<html lang="vi">
<head>
  <?php
    $defaultTitle = site_config('default_meta_title', site_config('name'));
    $defaultDescription = site_config('default_meta_description', '');
    $defaultOgImage = site_config('default_og_image') ?: frontend_asset('assets/images/hero-industrial.webp');
    $favicon = site_config('site_favicon');
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
  <title><?= e($pageTitle ?? $defaultTitle) ?></title>
  <meta name="description" content="<?= e($pageDescription ?? $defaultDescription) ?>">
  <meta name="keywords" content="<?= e(site_config('default_meta_keywords', '')) ?>">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="<?= e($canonicalUrl ?? site_config('site_url')) ?>">
  <meta property="og:locale" content="vi_VN">
  <meta property="og:type" content="<?= e($ogType ?? 'website') ?>">
  <meta property="og:title" content="<?= e($pageTitle ?? $defaultTitle) ?>">
  <meta property="og:description" content="<?= e($pageDescription ?? $defaultDescription) ?>">
  <meta property="og:url" content="<?= e($canonicalUrl ?? site_config('site_url')) ?>">
  <meta property="og:site_name" content="<?= e(site_config('name')) ?>">
  <meta property="og:image" content="<?= e($ogImage ?? absolute_url($defaultOgImage)) ?>">
  <meta name="twitter:card" content="summary_large_image">
  <?php if ($favicon): ?>
  <link rel="icon" href="<?= e(absolute_url($favicon)) ?>">
  <?php endif; ?>
  <?php if (site_config('google_site_verification')): ?>
  <meta name="google-site-verification" content="<?= e(site_config('google_site_verification')) ?>">
  <?php endif; ?>

  <?php if (!empty($jsonLd)): ?>
  <script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
  <?php endif; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.3.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="<?= e(frontend_asset('assets/css/style.css')) ?>" rel="stylesheet">
  <link href="<?= e(frontend_asset('assets/css/navigation.css')) ?>" rel="stylesheet">
  <?php foreach (($extraStyles ?? []) as $style): ?>
  <link href="<?= e(frontend_asset('assets/css/' . $style)) ?>" rel="stylesheet">
  <?php endforeach; ?>
</head>
<body class="<?= e($bodyClass ?? '') ?>">


