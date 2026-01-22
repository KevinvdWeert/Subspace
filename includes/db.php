<?php

declare(strict_types=1);

// Database connectie klasse met singleton patroon
final class Db
{
    private static ?PDO $pdo = null;

    // Haal de PDO instantie op of maak een nieuwe aan
    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        // Laad database configuratie
        $config = require __DIR__ . '/config.php';
        $db = $config['db'];

        // Bouw DSN string
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $db['host'],
            $db['name'],
            $db['charset']
        );

        // Maak PDO connectie met veilige opties
        $pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        self::$pdo = $pdo;
        return $pdo;
    }
}
