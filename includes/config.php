<?php

declare(strict_types=1);

// Copy/paste and adjust for your local Laragon setup.
// Do NOT commit real production secrets.

return [
    'app' => [
        // If your project runs under a subfolder like http://localhost/Subspace,
        // set this to '/Subspace'. Leave empty when running at domain root.
        'base_path' => '/projecten/Subspace',
    ],
    'db' => [
        'host' => '127.0.0.1',
        'name' => 'subspace',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
];
