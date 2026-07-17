<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? config('name')) ?></title>
  <meta name="description" content="<?= e($pageDescription ?? '') ?>">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="<?= e($canonicalUrl ?? config('site_url')) ?>">
  <meta property="og:locale" content="vi_VN">
  <meta property="og:type" content="<?= e($ogType ?? 'website') ?>">
  <meta property="og:title" content="<?= e($pageTitle ?? config('name')) ?>">
  <meta property="og:description" content="<?= e($pageDescription ?? '') ?>">
  <meta property="og:url" content="<?= e($canonicalUrl ?? config('site_url')) ?>">
  <meta property="og:site_name" content="<?= e(config('name')) ?>">
  <meta property="og:image" content="<?= e($ogImage ?? absolute_url('public/assets/images/hero-industrial.webp')) ?>">
  <meta name="twitter:card" content="summary_large_image">

  <?php if (!empty($jsonLd)): ?>
  <script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?></script>
  <?php endif; ?>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.3.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link href="<?= e(asset('assets/css/style.css')) ?>" rel="stylesheet">
  <link href="<?= e(asset('assets/css/navigation.css')) ?>" rel="stylesheet">
  <?php foreach (($extraStyles ?? []) as $style): ?>
  <link href="<?= e(asset('assets/css/' . $style)) ?>" rel="stylesheet">
  <?php endforeach; ?>
</head>
<body class="<?= e($bodyClass ?? '') ?>">
