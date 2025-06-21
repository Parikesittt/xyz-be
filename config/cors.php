<?php
return [
    'supports_credentials' => true,

    'allowed_origins' => [
        'http://localhost:3001'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        '*'
    ],

    'allowed_methods' => ['*'],  // Mengizinkan semua metode HTTP (GET, POST, PUT, DELETE)
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
