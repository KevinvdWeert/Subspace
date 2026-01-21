<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function app_base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }

    $configFile = __DIR__ . '/config.php';
    if (!is_file($configFile)) {
        $base = '';
        return $base;
    }

    $config = require $configFile;
    $base = rtrim((string)($config['app']['base_path'] ?? ''), '/');
    return $base;
}

function url(string $path = '/'): string
{
    $path = (string)$path;

    // Allow absolute URLs
    if (preg_match('#^https?://#i', $path) === 1) {
        return $path;
    }

    if ($path === '') {
        $path = '/';
    }

    if ($path[0] !== '/') {
        $path = '/' . $path;
    }

    $base = trim(app_base_path());
    if ($base === '' || $base === '/') {
        return $path;
    }

    // If base is a full URL, join as URL.
    if (preg_match('#^https?://#i', $base) === 1) {
        return rtrim($base, '/') . $path;
    }

    // Otherwise treat it as a path prefix.
    return rtrim($base, '/') . $path;
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function require_method(string $method): void
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($method)) {
        http_response_code(405);
        exit('Method Not Allowed');
    }
}

function now_datetime(): string
{
    return (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
}
function create_space(int $user_id, string $title, string $subject, ?string $description = null): int
{
    require_once __DIR__ . '/db.php';
    
    $pdo = Db::pdo();
    $stmt = $pdo->prepare(
        'INSERT INTO spaces (user_id, title, subject, description, is_hidden, created_at)
         VALUES (:user_id, :title, :subject, :description, 0, :created_at)'
    );
    
    $stmt->execute([
        ':user_id' => $user_id,
        ':title' => $title,
        ':subject' => $subject,
        ':description' => $description ?? '',
        ':created_at' => now_datetime(),
    ]);
    
    return (int)$pdo->lastInsertId();
}

function get_space(int $space_id): ?array
{
    require_once __DIR__ . '/db.php';
    
    $pdo = Db::pdo();
    $stmt = $pdo->prepare(
        'SELECT s.id, s.user_id, s.title, s.subject, s.description, s.is_hidden, s.created_at, s.updated_at,
                u.username
         FROM spaces s
         JOIN users u ON u.id = s.user_id
         WHERE s.id = :id'
    );
    
    $stmt->execute([':id' => $space_id]);
    $space = $stmt->fetch();
    
    return $space ?: null;
}

function get_spaces(int $limit = 20, int $offset = 0, bool $include_hidden = false): array
{
    require_once __DIR__ . '/db.php';
    
    $pdo = Db::pdo();
    $hidden_clause = $include_hidden ? '' : 'AND s.is_hidden = 0';
    
    $stmt = $pdo->prepare(
        "SELECT s.id, s.user_id, s.title, s.subject, s.description, s.is_hidden, s.created_at,
                u.username, COUNT(p.id) AS post_count
         FROM spaces s
         JOIN users u ON u.id = s.user_id
         LEFT JOIN posts p ON p.space_id = s.id AND p.is_hidden = 0
         WHERE 1=1 $hidden_clause
         GROUP BY s.id
         ORDER BY s.created_at DESC
         LIMIT :limit OFFSET :offset"
    );
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}

function get_space_posts(int $space_id, int $limit = 20, int $offset = 0): array
{
    require_once __DIR__ . '/db.php';
    
    $pdo = Db::pdo();
    $stmt = $pdo->prepare(
        'SELECT p.id, p.user_id, p.content, p.link_url, p.media_url, p.is_hidden, p.created_at,
                u.username
         FROM posts p
         JOIN users u ON u.id = p.user_id
         WHERE p.space_id = :space_id AND p.is_hidden = 0
         ORDER BY p.created_at DESC
         LIMIT :limit OFFSET :offset'
    );
    
    $stmt->bindValue(':space_id', $space_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll();
}