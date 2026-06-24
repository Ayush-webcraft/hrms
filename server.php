<?php
/**
 * Router for PHP's built-in web server (no Apache needed):
 *
 *     php -S localhost:8000 server.php
 *
 * Serves real files (assets) directly and sends everything else to index.php.
 */

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

// Let the built-in server handle existing static files itself.
if ($path !== '/' && is_file($file)) {
    // Block direct access to the database / source from the dev server too.
    if (preg_match('/\.(sqlite|sqlite-shm|sqlite-wal|sql|php)$/i', $path) && !str_ends_with($path, 'index.php')) {
        http_response_code(404);
        return true;
    }
    return false;
}

require __DIR__ . '/index.php';
