<?php
// Database configuration for local XAMPP usage.
// You can override these with environment variables.
return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'database' => getenv('DB_NAME') ?: 'babybrands_db',
    'port' => (int) (getenv('DB_PORT') ?: 3306),
];
