<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user = current_user();

$currentPath = strtolower((string)($_SERVER['SCRIPT_NAME'] ?? ''));
$isActive = static function (string $path) use ($currentPath): string {
    $path = strtolower($path);
    if ($path !== '' && $path[0] !== '/') {
        $path = '/' . $path;
    }

    return str_ends_with($currentPath, $path) ? 'active' : '';
};
$isAdminSection = str_starts_with($currentPath, '/admin/');

// Lightweight spaces list for navigation.
try {
    $navSpaces = get_spaces(200, 0, is_admin());
} catch (Throwable $e) {
    $navSpaces = [];
}

// seo setup
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = (string)($_SERVER['HTTP_HOST'] ?? 'localhost');
$requestUri = (string)($_SERVER['REQUEST_URI'] ?? '/');
$defaultCanonical = $scheme . '://' . $host . $requestUri;

$seo = (isset($seo) && is_array($seo)) ? $seo : [];
$seoTitle = (string)($seo['title'] ?? 'Subspace');
$seoDescription = (string)($seo['description'] ?? 'Subspace — explore spaces, share posts, and connect.');
$seoRobots = (string)($seo['robots'] ?? 'index, follow');
$seoCanonical = (string)($seo['canonical'] ?? $defaultCanonical);
$seoOgType = (string)($seo['og_type'] ?? 'website');
$seoOgImage = isset($seo['og_image']) ? (string)$seo['og_image'] : '';
$seoTwitterCard = (string)($seo['twitter_card'] ?? 'summary');
?>

<!-- html structure -->
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($seoTitle) ?></title>

    <meta name="description" content="<?= e($seoDescription) ?>">
    <meta name="robots" content="<?= e($seoRobots) ?>">
    <link rel="canonical" href="<?= e($seoCanonical) ?>">

    <meta property="og:site_name" content="Subspace">
    <meta property="og:type" content="<?= e($seoOgType) ?>">
    <meta property="og:title" content="<?= e($seoTitle) ?>">
    <meta property="og:description" content="<?= e($seoDescription) ?>">
    <meta property="og:url" content="<?= e($seoCanonical) ?>">
    <?php if ($seoOgImage !== ''): ?>
        <meta property="og:image" content="<?= e($seoOgImage) ?>">
    <?php endif; ?>

    <meta name="twitter:card" content="<?= e($seoTwitterCard) ?>">
    <meta name="twitter:title" content="<?= e($seoTitle) ?>">
    <meta name="twitter:description" content="<?= e($seoDescription) ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= e(url('/assets/css/style.css')) ?>">
    <script src="<?= e(url('/assets/js/script.js')) ?>" defer></script>
</head>

<body>
<div class="d-flex min-vh-100">
    <aside class="d-flex flex-column flex-shrink-0 p-3 border-end bg-body-tertiary subspace-sidebar">
        <a class="d-flex align-items-center gap-2 mb-3 text-decoration-none" href="<?= e(url('/index.php')) ?>" aria-label="Subspace home">
            <span class="brand-mark" aria-hidden="true">S</span>
            <span class="brand-text">SUBSPACE</span>
        </a>

        <ul class="nav nav-pills flex-column gap-1 mb-3">
            <li class="nav-item">
                <a class="nav-link <?= e($isActive('index.php')) ?>" href="<?= e(url('/index.php')) ?>">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= e($isActive('space.php')) ?>" href="<?= e(url('/space.php')) ?>">Spaces</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= e($isActive('search.php')) ?>" href="<?= e(url('/search.php')) ?>">Search</a>
            </li>
            <?php if ($user): ?>
                <li class="nav-item">
                    <a class="nav-link <?= e($isActive('profile.php')) ?>" href="<?= e(url('/profile.php')) ?>">profiel</a>
                </li>
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $isAdminSection ? 'active' : '' ?>" href="<?= e(url('/admin/index.php')) ?>">Admin</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>

        <div class="text-uppercase small fw-semibold text-secondary mb-2">Spaces</div>
        <div class="nav flex-column small gap-1 subspace-nav-spaces">
            <a class="nav-link px-0" href="<?= e(url('/space.php')) ?>">All Spaces</a>
            <?php foreach ($navSpaces as $space): ?>
                <a class="nav-link px-0" href="<?= e(url('/space.php?id=' . (int)$space['id'])) ?>">
                    <?= e($space['title']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($user): ?>
            <a class="btn btn-sm btn-outline-primary w-100 mt-2" href="<?= e(url('/space.php#new-space')) ?>">+ Create Space</a>
        <?php endif; ?>

        <div class="pt-3 mt-3 border-top">
            <?php if ($user): ?>
                <div class="small text-secondary">Signed in as</div>
                <div class="mb-2">
                    <a class="text-decoration-none" href="<?= e(url('/profile.php')) ?>"><?= e($user['username'] ?? '') ?></a>
                </div>
                <a class="btn btn-sm btn-outline-secondary w-100" href="<?= e(url('/logout.php')) ?>">Logout</a>
            <?php else: ?>
                <div class="d-grid gap-2">
                    <a class="btn btn-sm btn-primary" href="<?= e(url('/login.php')) ?>">Login</a>
                    <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('/register.php')) ?>">Create account</a>
                </div>
            <?php endif; ?>
        </div>
    </aside>

    <div class="flex-grow-1 d-flex flex-column">
        <main class="container-fluid py-3 flex-grow-1">
