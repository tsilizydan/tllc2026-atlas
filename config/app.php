<?php
/**
 * TSILIZY CORE - Application Configuration
 * Core constants and settings
 */

// Application info
define('APP_NAME', 'TSILIZY CORE');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development | production

// Path definitions
define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CORE_PATH', ROOT_PATH . '/core');
define('MODELS_PATH', ROOT_PATH . '/models');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('VIEWS_PATH', ROOT_PATH . '/views');
define('PRINTS_PATH', ROOT_PATH . '/prints');
define('ASSETS_PATH', ROOT_PATH . '/public/assets');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// URL Configuration (adjust for your environment)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Get base path without /public suffix
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = rtrim($scriptPath, '/');

// Remove /public from base path if present (for cleaner URLs)
if (substr($basePath, -7) === '/public') {
    $basePath = substr($basePath, 0, -7);
}

define('BASE_URL', $protocol . '://' . $host . $basePath);
define('ASSETS_URL', BASE_URL . '/public/assets');
define('UPLOADS_URL', BASE_URL . '/uploads');

// Session configuration
define('SESSION_NAME', 'TSILIZY_CORE_SESSION');
define('SESSION_LIFETIME', 7200); // 2 hours
define('SESSION_PATH', '/');
define('SESSION_SECURE', false); // Set to true in production with HTTPS
define('SESSION_HTTPONLY', true);

// Security settings
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_COST', 12);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Upload settings
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_DOC_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx']);
define('PLACEHOLDER_AVATAR', 'images/placeholder-avatar.svg');
define('PLACEHOLDER_LOGO', 'images/placeholder-logo.svg');
define('DANGEROUS_EXTENSIONS', ['php', 'php3', 'php4', 'php5', 'phtml', 'phar', 'exe', 'sh', 'bat', 'cmd']);
define('IMAGE_MIME_MAP', [
    'image/jpeg' => ['jpg', 'jpeg'],
    'image/png' => ['png'],
    'image/gif' => ['gif'],
    'image/webp' => ['webp'],
]);

// Pagination
define('ITEMS_PER_PAGE', 15);

// Date/Time
define('DEFAULT_TIMEZONE', 'Africa/Nairobi');
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('DISPLAY_DATE_FORMAT', 'd M Y');
define('DISPLAY_DATETIME_FORMAT', 'd M Y H:i');

// Set timezone
date_default_timezone_set(DEFAULT_TIMEZONE);

// Error reporting - production-safe (index.php sets ini earlier for bootstrap)
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');

// Ensure logs directory exists and is writable
$logsPath = ROOT_PATH . '/logs';
if (!is_dir($logsPath)) {
    @mkdir($logsPath, 0755, true);
}
if (is_dir($logsPath) && is_writable($logsPath)) {
    ini_set('error_log', $logsPath . '/error.log');
}

// Ensure uploads directories exist
$uploadsPath = ROOT_PATH . '/uploads';
$uploadDirs = ['avatars', 'company', 'receipts', 'contracts'];
if (!is_dir($uploadsPath)) {
    @mkdir($uploadsPath, 0755, true);
}
foreach ($uploadDirs as $dir) {
    $path = $uploadsPath . '/' . $dir;
    if (!is_dir($path)) {
        @mkdir($path, 0755, true);
    }
}

// Brand colors (for reference in PHP)
define('BRAND_COLORS', [
    'gold'     => '#C9A227',
    'black'    => '#000000',
    'charcoal' => '#0F0F0F',
    'gray'     => '#8E8E8E',
    'soft'     => '#E6E6E6',
    'white'    => '#FFFFFF'
]);
