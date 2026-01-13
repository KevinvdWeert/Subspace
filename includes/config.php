<?php

declare(strict_types=1);

// Copy/paste and adjust for your local Laragon setup.
// Do NOT commit real production secrets.

return [
    'app' => [
        // Keep paths relative to the project root folder.
        // NOTE: URL generation is handled dynamically in includes/helpers.php.
        // This value is kept for backward compatibility, but should stay empty.
        'base_path' => '',
    ],
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'subspace',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
];
