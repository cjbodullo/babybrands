<?php
// Database configuration for local XAMPP usage.
// You can override these with environment variables.

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'babybrands_db');
define('DB_PORT', 3306);

return [
    'host'      => DB_HOST,
    'username'  => DB_USER,
    'password'  => DB_PASS,
    'database'  => DB_NAME,
    'port'      => DB_PORT,
];
