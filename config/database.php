<?php

return [
    'connection' => env('DB_CONNECTION', 'sqlite'),

    'sqlite' => [
        'path' => dirname(__DIR__) . '/database/database.sqlite',
    ],

    'mysql' => [
        'host'     => env('DB_HOST', '127.0.0.1'),
        'port'     => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'llminvoice'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset'  => 'utf8mb4',
    ],
];
