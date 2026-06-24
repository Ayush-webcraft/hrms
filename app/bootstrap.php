<?php
/**
 * Bootstrap: load config, helpers, register the autoloader and start the session.
 * Everything (web entry point, migrate script, tests) goes through here.
 */

declare(strict_types=1);

// hrms/  (one level up from app/)
define('ROOT_PATH', dirname(__DIR__));

// --- Config -----------------------------------------------------------------
$GLOBALS['config'] = require ROOT_PATH . '/config/config.php';

// --- Error reporting --------------------------------------------------------
if ($GLOBALS['config']['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// --- Autoloader (core / models / controllers) -------------------------------
spl_autoload_register(function (string $class): void {
    $dirs = [
        ROOT_PATH . '/app/core/',
        ROOT_PATH . '/app/models/',
        ROOT_PATH . '/app/controllers/',
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});

// --- Helper functions -------------------------------------------------------
require ROOT_PATH . '/app/helpers/functions.php';

// --- Session ----------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
