<?php
/**
 * TSILIZY CORE - Database Configuration
 * MySQL Connection Settings
 */

// Database credentials
define('DB_HOST', 'sql105.byethost7.com');
define('DB_NAME', 'b7_40611962_tsilizy_core');
define('DB_USER', 'b7_40611962');
define('DB_PASS', 'RamTsida@2898');
define('DB_CHARSET', 'utf8mb4');

// PDO connection options
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_PERSISTENT         => false
]);
