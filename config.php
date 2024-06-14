<?php

return [
    'db_type' => env('DB_TYPE', 'sqlite'),
    'db_mysqli' => env('DB_TYPE', 'mysql') === 'mysql' ? env('MYSQLI', false) : false,
    'sqlite_path' => __ROOT__.env('DB_PATH', '/database/database.sqlite'),
    'db_host' => env('DB_HOST', 'localhost'),
    'db_username' => env('DB_USER', 'root'),
    'db_password' => env('DB_PASS', ''),
    'db_database' => env('DB_DATABASE', 'Kim'),
];
