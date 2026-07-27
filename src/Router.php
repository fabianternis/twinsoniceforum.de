<?php

namespace App;

class Router
{
    private static array $routes = [];

    public static function get(string $path, callable|array $handler): void
    {
        self::$routes['GET'][$path] = $handler;
    }

    public static function post(string $path, callable|array $handler): void
    {
        self::$routes['POST'][$path] = $handler;
    }

    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        // Normalize trailing slashes
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        // Direct match
        if (isset(self::$routes[$method][$uri])) {
            self::invoke(self::$routes[$method][$uri]);
            return;
        }

        // Pattern matching for dynamic parameters (e.g. /topic/123, /category/eiskunstlauf, /user/EmiliaFan)
        foreach (self::$routes[$method] ?? [] as $route => $handler) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                self::invoke($handler, $matches);
                return;
            }
        }

        // Fallback 404
        header("HTTP/1.0 404 Not Found");
        echo "404 - Seite nicht gefunden.";
    }

    private static function invoke(callable|array $handler, array $params = []): void
    {
        if (is_array($handler)) {
            [$class, $method] = $handler;
            call_user_func_array([$class, $method], $params);
        } else {
            call_user_func_array($handler, $params);
        }
    }
}
