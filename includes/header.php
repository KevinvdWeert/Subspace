<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subspace</title>
    <link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body style="color: var(--text-color);">
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
    <a class="navbar-brand text-white" href="<?= e(url('index.php')) ?>">Subspace</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav" style="color: var(--primary-color);">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="<?= e(url('index.php')) ?>">Feed</a></li>
            <?php if ($user): ?>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('profile.php')) ?>">Profiel</a></li>
                <?php if (($user['role'] ?? '') === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('admin/index.php')) ?>">Admin</a></li>
                <?php endif; ?>
            <?php endif; ?>
        </ul>

        <ul class="navbar-nav" style="color: var(--primary-color);">
            <?php if ($user): ?>
                <li class="nav-item"><span class="navbar-text mr-3">Ingelogd als <?= e($user['username'] ?? '') ?></span></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('logout.php')) ?>">Logout</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('login.php')) ?>">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(url('register.php')) ?>">Registreren</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<div class="container mt-4" style="color: var(--text-color);">