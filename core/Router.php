<?php
/**
 * TSILIZY CORE - Router
 * Request routing and dispatching
 */

class Router
{
    private static array $routes = [];
    private static array $params = [];

    /**
     * Load routes from config
     */
    public static function loadRoutes(): void
    {
        self::$routes = require CONFIG_PATH . '/routes.php';
    }

    /**
     * Get the current route path
     */
    public static function getPath(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        
        // Remove base path from URI
        if ($basePath !== '/' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Remove query string
        $path = parse_url($uri, PHP_URL_PATH) ?? '';
        $path = trim($path, '/');
        
        // Remove 'public/' prefix if present (for clean routing)
        if (strpos($path, 'public/') === 0) {
            $path = substr($path, 7);
        } elseif ($path === 'public') {
            $path = '';
        }
        
        return $path;
    }

    /**
     * Get query parameters
     */
    public static function getParams(): array
    {
        return self::$params;
    }

    /**
     * Get a specific parameter
     */
    public static function getParam(string $key, mixed $default = null): mixed
    {
        return self::$params[$key] ?? $_GET[$key] ?? $default;
    }

    /**
     * Dispatch the current request
     */
    public static function dispatch(): void
    {
        self::loadRoutes();
        
        $path = self::getPath();
        $method = $_SERVER['REQUEST_METHOD'];

        // Find matching route
        if (isset(self::$routes[$path])) {
            $route = self::$routes[$path];
            self::executeRoute($route['controller'], $route['action']);
            return;
        }

        // Check for dynamic routes (with ID parameter)
        foreach (self::$routes as $routePath => $route) {
            $pattern = preg_replace('/\{([a-z_]+)\}/', '([^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                self::$params = $matches;
                self::executeRoute($route['controller'], $route['action']);
                return;
            }
        }

        // No route found - 404
        self::notFound();
    }

    /**
     * Execute a route
     */
    private static function executeRoute(string $controllerName, string $actionName): void
    {
        $controllerFile = CONTROLLERS_PATH . '/' . $controllerName . '.php';
        
        if (!file_exists($controllerFile)) {
            self::notFound();
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            self::notFound();
            return;
        }

        $controller = new $controllerName();
        
        if (!method_exists($controller, $actionName)) {
            self::notFound();
            return;
        }

        // Call the action
        call_user_func_array([$controller, $actionName], self::$params);
    }

    /**
     * Handle 404 Not Found
     */
    public static function notFound(): void
    {
        http_response_code(404);
        
        if (file_exists(VIEWS_PATH . '/errors/404.php')) {
            require VIEWS_PATH . '/errors/404.php';
        } else {
            echo '<h1>404 - Page Not Found</h1>';
            echo '<p>The requested page could not be found.</p>';
            echo '<a href="' . BASE_URL . '">Return to Dashboard</a>';
        }
        exit;
    }

    /**
     * Handle 403 Forbidden
     */
    public static function forbidden(): void
    {
        http_response_code(403);
        
        if (file_exists(VIEWS_PATH . '/errors/403.php')) {
            require VIEWS_PATH . '/errors/403.php';
        } else {
            echo '<h1>403 - Access Forbidden</h1>';
            echo '<p>You do not have permission to access this page.</p>';
            echo '<a href="' . BASE_URL . '">Return to Dashboard</a>';
        }
        exit;
    }

    /**
     * Handle 500 Server Error
     */
    public static function serverError(string $message = ''): void
    {
        http_response_code(500);
        
        if (file_exists(VIEWS_PATH . '/errors/500.php')) {
            $errorMessage = APP_ENV === 'development' ? $message : '';
            require VIEWS_PATH . '/errors/500.php';
        } else {
            echo '<h1>500 - Server Error</h1>';
            echo '<p>An internal server error occurred.</p>';
            if (APP_ENV === 'development' && $message) {
                echo '<pre>' . htmlspecialchars($message) . '</pre>';
            }
            echo '<a href="' . BASE_URL . '">Return to Dashboard</a>';
        }
        exit;
    }

    /**
     * Generate URL for a route
     */
    public static function url(string $path, array $params = []): string
    {
        $url = BASE_URL . '/' . ltrim($path, '/');
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }

    /**
     * Check if current route matches
     */
    public static function isRoute(string $path): bool
    {
        return self::getPath() === ltrim($path, '/');
    }

    /**
     * Check if current route starts with
     */
    public static function routeStartsWith(string $prefix): bool
    {
        return strpos(self::getPath(), ltrim($prefix, '/')) === 0;
    }
}
