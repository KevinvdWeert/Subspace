<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function current_user(): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $userId = (int)$_SESSION['user_id'];
    $pdo = Db::pdo();

    $stmt = $pdo->prepare('SELECT id, username, email, role, created_at FROM users WHERE id = :id');
    $stmt->execute([':id' => $userId]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

function is_admin(): bool
{
    $user = current_user();
    return $user && ($user['role'] ?? '') === 'admin';
}

function require_admin(): void
{
    if (!is_admin()) {
        http_response_code(403);
        exit('Forbidden');
    }
}

function active_user_block(?int $userId = null): ?array
{
    if ($userId === null) {
        $user = current_user();
        if (!$user) {
            return null;
        }
        $userId = (int)$user['id'];
    }

    $pdo = Db::pdo();

    $stmt = $pdo->prepare(
        'SELECT id, user_id, blocked_by_admin_id, reason, blocked_until, created_at, revoked_at
         FROM user_blocks
         WHERE user_id = :user_id
           AND revoked_at IS NULL
           AND (blocked_until IS NULL OR blocked_until > NOW())
         ORDER BY created_at DESC
         LIMIT 1'
    );

    $stmt->execute([':user_id' => $userId]);
    $block = $stmt->fetch();

    return $block ?: null;
}

function require_not_blocked(): void
{
    $user = current_user();
    if (!$user) {
        return;
    }

    if (active_user_block((int)$user['id'])) {
        http_response_code(403);
        exit('Account is blocked');
    }
}
