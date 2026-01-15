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
    <link rel="stylesheet" href="<?= e(url('/assets/css/style.css')) ?>">
</head>

<body>
<nav>
    <ul>
        <li><a href="<?= e(url('/index.php')) ?>"><strong>Subspace</strong></a></li>
        <li><a href="<?= e(url('/index.php')) ?>">Feed</a></li>
        <?php if ($user): ?>
            <li><a href="<?= e(url('/profile.php')) ?>">Profiel</a></li>
            <?php if (($user['role'] ?? '') === 'admin'): ?>
                <li><a href="<?= e(url('/admin/index.php')) ?>">Admin</a></li>
            <?php endif; ?>
        <?php endif; ?>
        <li style="margin-left: auto;">
            <?php if ($user): ?>
                <span style="color: var(--text-muted); padding: 0 16px;">Ingelogd als <?= e($user['username'] ?? '') ?></span>
            <?php endif; ?>
        </li>
        <?php if ($user): ?>
            <li><a href="<?= e(url('/logout.php')) ?>">Logout</a></li>
        <?php else: ?>
            <li><a href="<?= e(url('/login.php')) ?>">Login</a></li>
            <li><a href="<?= e(url('/register.php')) ?>">Registreren</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    