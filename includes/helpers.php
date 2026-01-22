<?php

declare(strict_types=1);

// Escape HTML karakters voor veilige output
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Haal het basis pad van de applicatie op uit config
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

// Genereer een volledige URL met basis pad
function url(string $path = '/'): string
{
    $path = (string)$path;

    // Sta absolute URLs toe
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

    // Als base een volledige URL is, voeg samen als URL
    if (preg_match('#^https?://#i', $base) === 1) {
        return rtrim($base, '/') . $path;
    }

    // Anders behandel het als pad prefix
    return rtrim($base, '/') . $path;
}

// Stuur de gebruiker door naar een andere pagina
function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

// Vereis een specifieke HTTP methode
function require_method(string $method): void
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== strtoupper($method)) {
        http_response_code(405);
        exit('Method Not Allowed');
    }
}

// Geef de huidige datum en tijd terug als string
function now_datetime(): string
{
    return (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
}

/**
 * Lichtgewicht schema controle om pagina's werkend te houden tijdens DB migratie.
 */
function db_has_column(string $table, string $column): bool
{
    static $cache = [];

    $key = $table . '.' . $column;
    if (array_key_exists($key, $cache)) {
        return (bool)$cache[$key];
    }

    require_once __DIR__ . '/db.php';
    $pdo = Db::pdo();

    $stmt = $pdo->prepare(
        'SELECT 1
         FROM INFORMATION_SCHEMA.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = :t
           AND COLUMN_NAME = :c
         LIMIT 1'
    );
    $stmt->execute([':t' => $table, ':c' => $column]);

    $cache[$key] = (bool)$stmt->fetchColumn();
    return (bool)$cache[$key];
}

// Maak een nieuwe space aan en retourneer het ID
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

// Haal een specifieke space op met gebruikersinformatie
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

// Haal een lijst van spaces op met post aantallen
function get_spaces(int $limit = 20, int $offset = 0, bool $include_hidden = false): array
{
    require_once __DIR__ . '/db.php';

    $pdo = Db::pdo();
    $hidden_clause = $include_hidden ? '' : 'AND s.is_hidden = 0';

    $hasSpaceId = db_has_column('posts', 'space_id');

    if ($hasSpaceId) {
        $sql =
            "SELECT s.id, s.user_id, s.title, s.subject, s.description, s.is_hidden, s.created_at,
                    u.username, COUNT(p.id) AS post_count
             FROM spaces s
             JOIN users u ON u.id = s.user_id
             LEFT JOIN posts p ON p.space_id = s.id AND p.is_hidden = 0
             WHERE 1=1 $hidden_clause
             GROUP BY s.id
             ORDER BY s.created_at DESC
             LIMIT :limit OFFSET :offset";
    } else {
        // Oude DB schema: geen posts.space_id => kan posts per space niet veilig tellen
        $sql =
            "SELECT s.id, s.user_id, s.title, s.subject, s.description, s.is_hidden, s.created_at,
                    u.username, 0 AS post_count
             FROM spaces s
             JOIN users u ON u.id = s.user_id
             WHERE 1=1 $hidden_clause
             ORDER BY s.created_at DESC
             LIMIT :limit OFFSET :offset";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

// Haal alle posts op voor een specifieke space
function get_space_posts(int $space_id, int $limit = 20, int $offset = 0): array
{
    require_once __DIR__ . '/db.php';

    if (!db_has_column('posts', 'space_id')) {
        return [];
    }

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

/**
 * Sla een geüploade afbeelding op en retourneer het publieke pad (bijv. /assets/uploads/<naam>.jpg).
 * Retourneert null als er geen bestand werd geüpload.
 */
function save_uploaded_image(string $fieldName, int $maxBytes = 5242880): ?string
{
    if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
        return null;
    }

    $error = (int)($_FILES[$fieldName]['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($error === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($error !== UPLOAD_ERR_OK) {
        throw new RuntimeException('upload_failed');
    }

    $tmpName = (string)($_FILES[$fieldName]['tmp_name'] ?? '');
    $size = (int)($_FILES[$fieldName]['size'] ?? 0);
    if ($tmpName === '' || $size <= 0) {
        throw new RuntimeException('upload_invalid');
    }
    if ($size > $maxBytes) {
        throw new RuntimeException('upload_too_large');
    }
    if (!is_uploaded_file($tmpName)) {
        throw new RuntimeException('upload_invalid');
    }

    // Valideer mime type en afbeelding structuur
    if (!function_exists('finfo_open')) {
        throw new RuntimeException('upload_server_missing_finfo');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string)$finfo->file($tmpName);
    $extMap = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (!isset($extMap[$mime])) {
        throw new RuntimeException('upload_type_not_allowed');
    }
    if (@getimagesize($tmpName) === false) {
        throw new RuntimeException('upload_type_not_allowed');
    }

    // Maak upload directory aan indien nodig
    $uploadDirFs = dirname(__DIR__) . '/assets/uploads';
    if (!is_dir($uploadDirFs)) {
        if (!mkdir($uploadDirFs, 0755, true) && !is_dir($uploadDirFs)) {
            throw new RuntimeException('upload_server_no_dir');
        }
    }

    // Genereer unieke bestandsnaam
    $fileName = bin2hex(random_bytes(16)) . '.' . $extMap[$mime];
    $destFs = $uploadDirFs . '/' . $fileName;
    if (!move_uploaded_file($tmpName, $destFs)) {
        throw new RuntimeException('upload_move_failed');
    }

    return '/assets/uploads/' . $fileName;
}

/**
 * Valideer en normaliseer een externe media URL (opgeslagen zoals het is en gerenderd door de browser).
 * Retourneert null als leeg.
 */
function normalize_media_url(?string $value, int $maxLength = 500): ?string
{
    $value = trim((string)($value ?? ''));
    if ($value === '') {
        return null;
    }
    if (strlen($value) > $maxLength) {
        throw new RuntimeException('media_url_too_long');
    }
    if (!filter_var($value, FILTER_VALIDATE_URL)) {
        throw new RuntimeException('media_url_invalid');
    }

    // Controleer of het een veilig protocol is
    $parts = parse_url($value);
    $scheme = strtolower((string)($parts['scheme'] ?? ''));
    if (!in_array($scheme, ['http', 'https'], true)) {
        throw new RuntimeException('media_url_invalid');
    }

    return $value;
}