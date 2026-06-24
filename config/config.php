<?php
/**
 * Application configuration.
 *
 * Every value can be overridden with an environment variable of the same name,
 * which makes the app friendly to Docker / CI without editing this file.
 */

return [
    'app' => [
        'name'  => getenv('APP_NAME') ?: 'HRMS — HR Attendance & Payroll',
        'env'   => getenv('APP_ENV') ?: 'local',
        'debug' => filter_var(getenv('APP_DEBUG') ?: 'true', FILTER_VALIDATE_BOOLEAN),
    ],

    /*
     * Database driver: 'sqlite' (zero setup, default) or 'mysql' (per the spec).
     * Switch to MySQL by setting:  DB_DRIVER=mysql
     */
    'db' => [
        'driver' => getenv('DB_DRIVER') ?: 'sqlite',

        // --- MySQL settings (used when driver = mysql) ---
        'host'     => getenv('DB_HOST') ?: '127.0.0.1',
        'port'     => getenv('DB_PORT') ?: '3306',
        'database' => getenv('DB_DATABASE') ?: 'hrms_db',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'charset'  => 'utf8mb4',

        // --- SQLite settings (used when driver = sqlite) ---
        'sqlite_path' => ROOT_PATH . '/database/hrms.sqlite',
    ],

    /*
     * Payroll / leave business rules.
     */
    'payroll' => [
        'currency'            => getenv('CURRENCY') ?: '₹',
        'standard_work_hours' => 8,     // hours/day before overtime kicks in
        'overtime_multiplier' => 1.5,   // overtime paid at 1.5× the hourly rate
        'annual_leave_quota'  => 24,    // paid leave days per employee per year
    ],
];
