<?php

declare(strict_types=1);

/**
 * Minimal router. Routes map an HTTP method + path pattern to a
 * "Controller@method" action, with an optional list of middleware names
 * ('auth', 'admin', 'employee', 'guest').
 *
 * Path params use the :name syntax, e.g. /employees/:id/edit.
 */
class Router
{
    private array $routes = [];

    public function get(string $path, string $action, array $middleware = []): void
    {
        $this->add('GET', $path, $action, $middleware);
    }

    public function post(string $path, string $action, array $middleware = []): void
    {
        $this->add('POST', $path, $action, $middleware);
    }

    private function add(string $method, string $path, string $action, array $middleware): void
    {
        $this->routes[] = compact('method', 'path', 'action', 'middleware');
    }

    /** Resolve the current request path relative to the app base path. */
    private function currentPath(): string
    {
        $uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = base_path();
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        return '/' . trim($uri, '/');
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path   = $this->currentPath();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            $params = $this->match($route['path'], $path);
            if ($params === null) {
                continue;
            }

            // CSRF protection on all state-changing requests.
            if ($method === 'POST' && !verify_csrf()) {
                http_response_code(419);
                exit('Page expired (invalid CSRF token). Go back and try again.');
            }

            $this->runMiddleware($route['middleware']);

            [$controller, $action] = explode('@', $route['action']);
            (new $controller())->$action(...array_values($params));
            return;
        }

        $this->notFound();
    }

    /** Return captured params if $pattern matches $path, otherwise null. */
    private function match(string $pattern, string $path): ?array
    {
        $regex = preg_replace('#:([\w]+)#', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';
        if (!preg_match($regex, $path, $matches)) {
            return null;
        }
        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    private function runMiddleware(array $middleware): void
    {
        foreach ($middleware as $name) {
            switch ($name) {
                case 'auth':
                    Auth::requireAuth();
                    break;
                case 'admin':
                    Auth::requireRole('admin');
                    break;
                case 'employee':
                    Auth::requireRole('employee');
                    break;
                case 'guest':
                    if (Auth::check()) {
                        redirect('/dashboard');
                    }
                    break;
            }
        }
    }

    private function notFound(): void
    {
        http_response_code(404);
        $title = 'Page Not Found';
        require ROOT_PATH . '/app/views/errors/404.php';
    }
}
