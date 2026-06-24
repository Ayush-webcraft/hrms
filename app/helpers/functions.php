<?php
/**
 * Small global helpers used across controllers and views.
 */

declare(strict_types=1);

/** Read a dotted config key, e.g. config('payroll.currency'). */
function config(string $key, $default = null)
{
    $value = $GLOBALS['config'];
    foreach (explode('.', $key) as $segment) {
        if (is_array($value) && array_key_exists($segment, $value)) {
            $value = $value[$segment];
        } else {
            return $default;
        }
    }
    return $value;
}

/** HTML-escape a value for safe output in views. */
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/**
 * The application's base path (handles install in a subfolder like /hrms).
 * Empty string when served from a domain root or PHP's built-in server.
 */
function base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }
    if (PHP_SAPI === 'cli-server') {
        return $base = '';
    }
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    return $base = ($dir === '/' ? '' : rtrim($dir, '/'));
}

/** Build an application URL: url('/employees') -> /hrms/employees. */
function url(string $path = '/'): string
{
    return base_path() . '/' . ltrim($path, '/');
}

/** Build an asset URL: asset('css/style.css'). */
function asset(string $path): string
{
    return base_path() . '/assets/' . ltrim($path, '/');
}

/** Redirect to an application path and stop. */
function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

/** Format money with the configured currency symbol. */
function money($amount): string
{
    return config('payroll.currency') . number_format((float) $amount, 2);
}

// --- Flash messages ---------------------------------------------------------

function flash(string $key, ?string $message = null)
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

// --- "Old" input (re-fill forms after a validation error) -------------------

function old(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash_old(array $input): void
{
    $_SESSION['_old'] = $input;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

// --- CSRF -------------------------------------------------------------------

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['_csrf'] ?? '';
    return !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], (string) $token);
}
