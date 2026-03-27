<?php
/**
 * TSILIZY CORE - Database Configuration
 * MySQL Connection Settings
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'tsilscpx_tsilizy_core');
define('DB_USER', 'tsilscpx_chibi_admin');
define('DB_PASS', '9@UPN~I@O]Dw');
define('DB_CHARSET', 'utf8mb4');

// PDO connection options
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => false
]);
