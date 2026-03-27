<?php
/**
 * TSILIZY CORE - Session Management
 * Secure session handling
 */

class Session
{
    private static bool $started = false;

    /**
     * Start the session securely
     */
    public static function start(): void
    {
        if (self::$started) {
            return;
        }

        // Configure session settings
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_httponly', 1);

        session_name(SESSION_NAME);
        
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path'     => SESSION_PATH,
            'secure'   => SESSION_SECURE,
            'httponly' => SESSION_HTTPONLY,
            'samesite' => 'Lax'
        ]);

        session_start();
        self::$started = true;

        // Regenerate ID periodically to prevent fixation
        if (!self::has('_last_regeneration')) {
            self::regenerate();
        } elseif (time() - self::get('_last_regeneration') > 1800) {
            self::regenerate();
        }
    }

    /**
     * Regenerate session ID
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
        self::set('_last_regeneration', time());
    }

    /**
     * Set a session value
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session value
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Clear all session data
     */
    public static function clear(): void
    {
        $_SESSION = [];
    }

    /**
     * Destroy the session completely
     */
    public static function destroy(): void
    {
        self::clear();
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        
        session_destroy();
        self::$started = false;
    }

    /**
     * Set a flash message
     */
    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][$type][] = $message;
    }

    /**
     * Get and clear flash messages
     */
    public static function getFlash(string $type = null): array
    {
        if ($type !== null) {
            $messages = $_SESSION['_flash'][$type] ?? [];
            unset($_SESSION['_flash'][$type]);
            return $messages;
        }

        $messages = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $messages;
    }

    /**
     * Check if there are flash messages
     */
    public static function hasFlash(string $type = null): bool
    {
        if ($type !== null) {
            return !empty($_SESSION['_flash'][$type]);
        }
        return !empty($_SESSION['_flash']);
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken(): string
    {
        if (!self::has(CSRF_TOKEN_NAME)) {
            self::set(CSRF_TOKEN_NAME, bin2hex(random_bytes(32)));
        }
        return self::get(CSRF_TOKEN_NAME);
    }

    /**
     * Validate CSRF token
     */
    public static function validateCsrfToken(string $token): bool
    {
        return hash_equals(self::get(CSRF_TOKEN_NAME, ''), $token);
    }

    /**
     * Get CSRF input field HTML
     */
    public static function csrfField(): string
    {
        $token = self::generateCsrfToken();
        return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
    }

    /**
     * Get CSRF token (alias for generateCsrfToken)
     */
    public static function getCsrfToken(): string
    {
        return self::generateCsrfToken();
    }

    /**
     * Validate CSRF token from POST (convenience method)
     */
    public static function validateCsrf(): void
    {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        if (!self::validateCsrfToken($token)) {
            self::flash('error', 'Invalid request. Please try again.');
            $referer = trim($_SERVER['HTTP_REFERER'] ?? '');
            $referer = ($referer !== '' && isSameOriginUrl($referer)) ? $referer : (rtrim(BASE_URL, '/') . '/');
            header('Location: ' . $referer);
            exit;
        }
    }
}

