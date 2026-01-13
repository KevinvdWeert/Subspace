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

function project_relative_prefix(): string
{
    static $prefix = null;
    if ($prefix !== null) {
        return $prefix;
    }

    $scriptName = (string)($_SERVER['SCRIPT_NAME'] ?? '');
    $scriptName = str_replace('\\', '/', $scriptName);

    $dir = trim(str_replace('\\', '/', (string)dirname($scriptName)), '/');
    if ($dir === '' || $dir === '.') {
        $prefix = '';
        return $prefix;
    }

    $parts = explode('/', $dir);

    // The project root folder name (expected: 'Subspace').
    $projectFolder = basename(dirname(__DIR__));

    $projectIndex = -1;
    foreach ($parts as $i => $part) {
        if ($part === $projectFolder) {
            $projectIndex = $i;
        }
    }

    if ($projectIndex === -1) {
        // Fallback: assume project is web-root; climb to root from current directory.
        $levels = count(array_filter($parts, static fn($p) => $p !== ''));
        $prefix = $levels > 0 ? str_repeat('../', $levels) : '';
        return $prefix;
    }

    $levelsAfterProject = count($parts) - ($projectIndex + 1);
    $prefix = $levelsAfterProject > 0 ? str_repeat('../', $levelsAfterProject) : '';
    return $prefix;
}

function url(string $path = '/'): string
{
    $path = (string)$path;

    // Allow absolute URLs
    if (preg_match('#^https?://#i', $path) === 1) {
        return $path;
    }

    $prefix = project_relative_prefix();

    // Normalize leading slash to keep everything relative to the project root.
    $normalized = ltrim($path, '/');
    if ($normalized === '' || $normalized === '.') {
        return $prefix;
    }

    return $prefix . $normalized;
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
