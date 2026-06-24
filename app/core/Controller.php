<?php

declare(strict_types=1);

/**
 * Base controller. Provides view rendering (wrapped in the shared layout)
 * and small request helpers.
 */
abstract class Controller
{
    /**
     * Render a view inside the header/footer layout.
     *
     * @param string $view  dotted view name, e.g. 'employees.index'
     * @param array  $data  variables made available to the view
     */
    protected function view(string $view, array $data = [], ?string $title = null): void
    {
        $data['title'] = $title ?? config('app.name');
        extract($data, EXTR_SKIP);

        $viewFile = ROOT_PATH . '/app/views/' . str_replace('.', '/', $view) . '.php';
        if (!is_file($viewFile)) {
            throw new RuntimeException("View not found: {$view}");
        }

        require ROOT_PATH . '/app/views/layouts/header.php';
        require $viewFile;
        require ROOT_PATH . '/app/views/layouts/footer.php';
    }

    /** Send a JSON response and stop. */
    protected function json($data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /** Read a trimmed POST field. */
    protected function input(string $key, $default = ''): string
    {
        $value = $_POST[$key] ?? $default;
        return is_string($value) ? trim($value) : (string) $value;
    }

    /** Require that the current request method is POST. */
    protected function requirePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit('Method Not Allowed');
        }
    }
}
