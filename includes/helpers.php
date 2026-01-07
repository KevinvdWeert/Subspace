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
