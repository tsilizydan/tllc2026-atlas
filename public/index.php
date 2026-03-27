<?php
/**
 * TSILIZY CORE - Application Entry Point
 * All requests are routed through this file
 */

// Security headers (must run before any output)
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Production-safe error handling (must run before any other code)
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');
ini_set('ignore_repeated_errors', '0');

$logDir = dirname(__DIR__) . '/logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
if (is_dir($logDir) && is_writable($logDir)) {
    ini_set('error_log', $logDir . '/error.log');
}

// Error handling
set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        return;
    }
    throw new ErrorException($message, 0, $severity, $file, $line);
});

set_exception_handler(function ($e) {
    error_log($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
    
    if (defined('APP_ENV') && APP_ENV === 'development') {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p><strong>File:</strong> ' . $e->getFile() . ' on line ' . $e->getLine() . '</p>';
        echo '<pre>' . $e->getTraceAsString() . '</pre>';
    } else {
        http_response_code(500);
        echo '<h1>Something went wrong</h1>';
        echo '<p>Please try again later.</p>';
    }
    exit;
});

// Load configuration
require_once dirname(__DIR__) . '/config/app.php';
require_once CONFIG_PATH . '/database.php';

// Autoload core classes
spl_autoload_register(function ($class) {
    $paths = [
        CORE_PATH . '/' . $class . '.php',
        MODELS_PATH . '/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load helpers
require_once CORE_PATH . '/helpers.php';

// Start session
Session::start();

// Route the request
try {
    Router::dispatch();
} catch (PDOException $e) {
    error_log('Database Error: ' . $e->getMessage());
    Router::serverError('Database error occurred.');
} catch (Exception $e) {
    error_log('Application Error: ' . $e->getMessage());
    Router::serverError($e->getMessage());
}
