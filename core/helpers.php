<?php
/**
 * TSILIZY CORE - Helper Functions
 * Utility functions used throughout the application
 */

/**
 * Generate a URL for a given path
 */
function url(string $path = '', array $params = []): string
{
    $url = rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    return $url;
}

/**
 * Check if current route matches given path
 */
function isCurrentRoute(string $path): bool
{
    $currentPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '/');
    $basePath = trim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
    
    if ($basePath) {
        $currentPath = str_replace($basePath . '/', '', $currentPath);
    }
    
    return $currentPath === ltrim($path, '/') || strpos($currentPath, ltrim($path, '/')) === 0;
}

/**
 * Check if request is POST
 */
function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if request is GET
 */
function isGet(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Get input value from POST or GET (raw; use inputSafe for strings to store)
 */
function input(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

/**
 * Get input value sanitized for safe storage (trim, strip tags)
 * Use for text/string inputs before saving to DB
 */
function inputSafe(string $key, mixed $default = null): mixed
{
    $val = input($key, $default);
    if (is_string($val)) {
        return trim(strip_tags($val));
    }
    return $val;
}

/**
 * Check if user has permission (alias)
 */
function hasPermission(string $module, string $action): bool
{
    return Auth::can($module, $action);
}

/**
 * Check if URL is same-origin (prevents open redirect)
 */
function isSameOriginUrl(string $url): bool
{
    $url = trim($url);
    if ($url === '') return false;
    // Relative path (same origin)
    if ($url[0] === '/' && (strlen($url) === 1 || $url[1] !== '/')) {
        return true;
    }
    // Protocol-relative (//evil.com) or absolute URL - must match our host
    $baseHost = parse_url(BASE_URL, PHP_URL_HOST);
    $parsed = parse_url($url);
    $urlHost = $parsed['host'] ?? null;
    return $urlHost === null || $urlHost === $baseHost;
}

/**
 * Redirect to a URL (internal paths only; prevents open redirect)
 */
function redirect(string $path, array $params = []): void
{
    $url = Router::url($path, $params);
    if (!isSameOriginUrl($url)) {
        $url = BASE_URL . '/';
    }
    header("Location: {$url}");
    exit;
}

/**
 * Redirect back to previous page (validates referer to prevent open redirect)
 */
function back(): void
{
    $referer = trim($_SERVER['HTTP_REFERER'] ?? '');
    if ($referer === '' || !isSameOriginUrl($referer)) {
        $referer = rtrim(BASE_URL, '/') . '/';
    }
    header("Location: {$referer}");
    exit;
}

/**
 * Escape HTML output (safe for strings; arrays/objects become empty string)
 */
function e(mixed $value): string
{
    if (is_array($value) || is_object($value)) {
        return '';
    }
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Get old form input
 */
function old(string $key, mixed $default = ''): mixed
{
    $input = Session::get('_old_input');
    if (!is_array($input)) {
        return $default;
    }
    return $input[$key] ?? $default;
}

/**
 * Store old form input
 */
function storeOldInput(): void
{
    Session::set('_old_input', $_POST);
}

/**
 * Clear old form input
 */
function clearOldInput(): void
{
    Session::remove('_old_input');
}

/**
 * Generate asset URL
 */
function asset(string $path): string
{
    return ASSETS_URL . '/' . ltrim($path, '/');
}

/**
 * Generate upload URL with fallback for empty/invalid paths
 * @param string|null $path Relative path (e.g. avatars/xxx.jpg)
 * @param string $fallback 'avatar'|'logo' - placeholder when path is empty
 */
function upload(?string $path, string $fallback = 'avatar'): string
{
    $path = trim($path ?? '');
    if ($path === '' || str_contains($path, '..')) {
        $placeholder = $fallback === 'logo' ? PLACEHOLDER_LOGO : PLACEHOLDER_AVATAR;
        return asset($placeholder);
    }
    return UPLOADS_URL . '/' . ltrim($path, '/');
}

/**
 * Format date for display
 */
function formatDate(?string $date, string $format = null): string
{
    if (empty($date)) {
        return '-';
    }
    
    $format = $format ?? DISPLAY_DATE_FORMAT;
    $datetime = new DateTime($date);
    return $datetime->format($format);
}

/**
 * Format datetime for display
 */
function formatDateTime(?string $datetime, string $format = null): string
{
    if (empty($datetime)) {
        return '-';
    }
    
    $format = $format ?? DISPLAY_DATETIME_FORMAT;
    $dt = new DateTime($datetime);
    return $dt->format($format);
}

/**
 * Format currency; accepts mixed amount, coerces to float
 */
function formatMoney(mixed $amount, string $currency = 'USD'): string
{
    $amount = is_numeric($amount) ? (float) $amount : 0.0;
    $symbols = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'KES' => 'KSh'
    ];
    
    $symbol = $symbols[$currency] ?? $currency . ' ';
    return $symbol . number_format($amount, 2);
}

/**
 * Format currency (alias for formatMoney); accepts mixed, coerces to float
 */
function formatCurrency(mixed $amount, string $currency = 'USD'): string
{
    $amount = is_numeric($amount) ? (float) $amount : 0.0;
    return formatMoney($amount, $currency);
}

/**
 * Format number with thousands separator; accepts mixed, coerces to float
 */
function formatNumber(mixed $number, int $decimals = 0): string
{
    $number = is_numeric($number) ? (float) $number : 0.0;
    return number_format($number, $decimals);
}

/**
 * Truncate text
 */
function truncate(?string $text, int $length = 100, string $suffix = '...'): string
{
    $text = $text ?? '';
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return rtrim(substr($text, 0, $length)) . $suffix;
}

/**
 * Generate a slug from string
 */
function slugify(?string $text): string
{
    $text = $text ?? '';
    $text = preg_replace('~[^\pL\d]+~u', '-', $text) ?? '';
    $text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text) ?: $text;
    $text = preg_replace('~[^-\w]+~', '', $text) ?? '';
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text) ?? '';
    $text = strtolower($text);
    
    return $text;
}

/**
 * Generate unique reference number
 */
function generateReference(string $prefix = '', int $length = 8): string
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $reference = $prefix;
    
    for ($i = 0; $i < $length; $i++) {
        $reference .= $characters[random_int(0, strlen($characters) - 1)];
    }
    
    return $reference;
}

/**
 * Generate invoice number
 */
function generateInvoiceNumber(): string
{
    $year = date('Y');
    $count = Database::fetchColumn(
        "SELECT COUNT(*) FROM invoices WHERE YEAR(created_at) = ?",
        [$year]
    );
    
    return 'INV-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
}

/**
 * Generate contract number
 */
function generateContractNumber(): string
{
    $year = date('Y');
    $count = Database::fetchColumn(
        "SELECT COUNT(*) FROM contracts WHERE YEAR(created_at) = ?",
        [$year]
    );
    
    return 'CON-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
}

/**
 * Generate employee code
 */
function generateEmployeeCode(): string
{
    $count = Database::count('employees');
    return 'EMP-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
}

/**
 * Check if request is AJAX
 */
function isAjax(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Send JSON response
 */
function jsonResponse(array $data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Get all request input
 */
function allInput(): array
{
    return array_merge($_GET, $_POST);
}

/**
 * Sanitize input
 */
function sanitize(?string $input): string
{
    return htmlspecialchars(trim($input ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * Sanitize array of inputs
 */
function sanitizeArray(array $inputs): array
{
    return array_map('sanitize', $inputs);
}

/**
 * Get file extension (lowercase, no path traversal)
 */
function getFileExtension(string $filename): string
{
    $name = basename($filename);
    $ext = pathinfo($name, PATHINFO_EXTENSION);
    return strtolower($ext ?: '');
}

/**
 * Check if file type is allowed image
 */
function isAllowedImage(string $filename): bool
{
    return in_array(getFileExtension($filename), ALLOWED_IMAGE_TYPES);
}

/**
 * Check if file type is allowed document
 */
function isAllowedDocument(string $filename): bool
{
    return in_array(getFileExtension($filename), ALLOWED_DOC_TYPES);
}

/**
 * Upload file with secure validation (MIME check, dangerous extension block)
 * @return string|null Relative path (e.g. avatars/xxx.jpg) or null on failure
 */
function uploadFile(array $file, string $directory, array $allowedTypes = []): ?string
{
    if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($file['size'] > MAX_UPLOAD_SIZE || $file['size'] <= 0) {
        return null;
    }

    $extension = getFileExtension($file['name']);
    if ($extension === '' || in_array($extension, DANGEROUS_EXTENSIONS, true)) {
        return null;
    }

    if (!empty($allowedTypes) && !in_array($extension, $allowedTypes)) {
        return null;
    }

    // MIME validation for images (prevents disguised scripts)
    if (!empty($allowedTypes) && count(array_intersect($allowedTypes, ALLOWED_IMAGE_TYPES)) > 0 && function_exists('finfo_open')) {
        $finfo = @finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mime = @finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if ($mime !== false && $mime !== '') {
                $mimeValid = false;
                foreach (IMAGE_MIME_MAP as $allowedMime => $exts) {
                    if ($mime === $allowedMime && in_array($extension, $exts, true)) {
                        $mimeValid = true;
                        break;
                    }
                }
                if (!$mimeValid) {
                    return null;
                }
            }
        }
    }

    $directory = preg_replace('/[^a-z0-9_-]/', '', strtolower(trim($directory, '/')));
    $directory = $directory !== '' ? $directory : 'files';
    $filename = bin2hex(random_bytes(8)) . '_' . time() . '.' . $extension;
    $targetDir = rtrim(UPLOADS_PATH, '/') . '/' . $directory;

    if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true)) {
        return null;
    }

    $targetPath = $targetDir . '/' . $filename;
    $relativePath = $directory . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $relativePath;
    }

    return null;
}

/**
 * Delete file
 */
function deleteFile(string $path): bool
{
    $fullPath = UPLOADS_PATH . '/' . ltrim($path, '/');
    
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    
    return false;
}

/**
 * Calculate age from date
 */
function calculateAge(string $birthDate): int
{
    $birth = new DateTime($birthDate);
    $today = new DateTime();
    return $today->diff($birth)->y;
}

/**
 * Get time ago string
 */
function timeAgo(?string $datetime): string
{
    if (empty($datetime)) {
        return '-';
    }
    $time = strtotime($datetime);
    if ($time === false) {
        return '-';
    }
    $diff = time() - $time;
    
    if ($diff < 60) {
        return 'just now';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' min' . ($mins > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return formatDate($datetime);
    }
}

/**
 * Get pagination data
 */
function paginate(int $totalItems, int $currentPage = 1, int $perPage = null): array
{
    $perPage = $perPage ?? ITEMS_PER_PAGE;
    $totalPages = max(1, ceil($totalItems / $perPage));
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;
    
    return [
        'total' => $totalItems,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
        'has_previous' => $currentPage > 1,
        'has_prev' => $currentPage > 1, // Alias for view compatibility
        'has_next' => $currentPage < $totalPages,
        'previous_page' => max(1, $currentPage - 1),
        'next_page' => min($totalPages, $currentPage + 1)
    ];
}

/**
 * Get status badge HTML
 */
function statusBadge(?string $status, ?string $type = null): string
{
    $status = $status ?? '';
    // Auto-detect type based on status if not provided
    if ($type === null) {
        $type = match(strtolower($status)) {
            'active', 'paid', 'completed', 'success', 'approved' => 'success',
            'pending', 'sent', 'in_progress', 'processing' => 'info',
            'draft', 'inactive', 'planning' => 'default',
            'on_hold', 'warning', 'cancelled', 'suspended' => 'warning',
            'overdue', 'failed', 'rejected', 'terminated', 'error' => 'danger',
            default => 'default'
        };
    }
    
    $colors = [
        'default' => 'bg-gray-100 text-gray-800',
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'danger' => 'bg-red-100 text-red-800',
        'info' => 'bg-blue-100 text-blue-800',
        'primary' => 'bg-amber-100 text-amber-800'
    ];
    
    $color = $colors[$type] ?? $colors['default'];
    
    // Format status for display (replace underscores, capitalize)
    $displayStatus = ucwords(str_replace('_', ' ', $status));
    
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">' 
        . e($displayStatus) . '</span>';
}

/**
 * Get invoice status type
 */
function invoiceStatusType(?string $status): string
{
    $status = $status ?? '';
    return match($status) {
        'paid' => 'success',
        'sent' => 'info',
        'draft' => 'default',
        'overdue' => 'danger',
        'cancelled' => 'warning',
        default => 'default'
    };
}

/**
 * Get project status type
 */
function projectStatusType(string $status): string
{
    return match($status) {
        'completed' => 'success',
        'active' => 'info',
        'planning' => 'default',
        'on_hold' => 'warning',
        default => 'default'
    };
}

/**
 * Get contract status type
 */
function contractStatusType(string $status): string
{
    return match($status) {
        'completed' => 'success',
        'active' => 'info',
        'draft' => 'default',
        'terminated' => 'danger',
        default => 'default'
    };
}

/**
 * Include a view partial
 */
function partial(string $name, array $data = []): void
{
    extract($data);
    require VIEWS_PATH . '/components/' . $name . '.php';
}

/**
 * Render a view
 */
function view(string $name, array $data = [], string $layout = 'app'): void
{
    extract($data);
    
    ob_start();
    require VIEWS_PATH . '/' . str_replace('.', '/', $name) . '.php';
    $content = ob_get_clean();
    
    // Check if the view specified a layout variable
    if (isset($GLOBALS['layout'])) {
        $layout = $GLOBALS['layout'];
        unset($GLOBALS['layout']);
    }
    
    require VIEWS_PATH . '/layouts/' . $layout . '.php';
}

/**
 * Render a print view
 */
function printView(string $name, array $data = []): void
{
    extract($data);
    
    // Capture content from the print template
    ob_start();
    require VIEWS_PATH . '/prints/' . $name . '.php';
    $content = ob_get_clean();
    
    // Render with print layout
    require VIEWS_PATH . '/layouts/print.php';
}

/**
 * Get the company profile
 */
function getCompanyProfile(): ?array
{
    return Database::fetch("SELECT * FROM company_profile ORDER BY id ASC LIMIT 1");
}


