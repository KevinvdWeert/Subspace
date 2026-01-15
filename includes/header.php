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
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    <ul>
        <li><a href="<?= e(url('/index.php')) ?>"><strong>Subspace</strong></a></li>
        <li><a href="<?= e(url('/index.php')) ?>">Feed</a></li>
=======
=======
>>>>>>> Stashed changes
    <a class="navbar-brand" href="<?= e(url('/index.php')) ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="reddit-logo">
            <g>
                <circle fill="#FF4500" cx="10" cy="10" r="10"></circle>
                <path fill="#FFF" d="M16.67,10A1.46,1.46,0,0,0,14.2,9a7.12,7.12,0,0,0-3.85-1.23L11,4.65,13.14,5.1a1,1,0,1,0,.13-0.61L10.82,4a0.31,0.31,0,0,0-.37.24L9.71,7.71a7.14,7.14,0,0,0-3.9,1.23A1.46,1.46,0,1,0,4.2,11.33a2.87,2.87,0,0,0,0,.44c0,2.24,2.61,4.06,5.83,4.06s5.83-1.82,5.83-4.06a2.87,2.87,0,0,0,0-.44A1.46,1.46,0,0,0,16.67,10Zm-10,1a1,1,0,1,1,1,1A1,1,0,0,1,6.67,11Zm5.81,2.75a3.84,3.84,0,0,1-2.47.77,3.84,3.84,0,0,1-2.47-.77,0.27,0.27,0,0,1,.38-0.38A3.27,3.27,0,0,0,10,14a3.28,3.28,0,0,0,2.09-.61A0.27,0.27,0,1,1,12.48,13.79Zm-0.18-1.71a1,1,0,1,1,1-1A1,1,0,0,1,12.29,12.08Z"></path>
            </g>
        </svg>
        <span class="reddit-text">Subspace</span>
    </a>

    <ul>
        <li><a href="<?= e(url('/index.php')) ?>">Home</a></li>
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
        <?php if ($user): ?>
            <li><a href="<?= e(url('/profile.php')) ?>">Profiel</a></li>
            <?php if (($user['role'] ?? '') === 'admin'): ?>
                <li><a href="<?= e(url('/admin/index.php')) ?>">Admin</a></li>
            <?php endif; ?>
        <?php endif; ?>
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        <li style="margin-left: auto;">
            <?php if ($user): ?>
                <span style="color: var(--text-muted); padding: 0 16px;">Ingelogd als <?= e($user['username'] ?? '') ?></span>
            <?php endif; ?>
        </li>
        <?php if ($user): ?>
            <li><a href="<?= e(url('/logout.php')) ?>">Logout</a></li>
=======
=======
>>>>>>> Stashed changes
    </ul>

    <ul style="margin-top: auto; border-top: 1px solid var(--border-color); padding-top: 8px;">
        <?php if ($user): ?>
            <li><a href="<?= e(url('/logout.php')) ?>">Logout</a></li>
            <li style="padding: 12px 16px; color: var(--text-muted); font-size: 12px;">
                Ingelogd als <strong><?= e($user['username'] ?? '') ?></strong>
            </li>
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
        <?php else: ?>
            <li><a href="<?= e(url('/login.php')) ?>">Login</a></li>
            <li><a href="<?= e(url('/register.php')) ?>">Registreren</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    