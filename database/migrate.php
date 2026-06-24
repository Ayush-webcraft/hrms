<?php
/**
 * Database setup & seeder.
 *
 *   php database/migrate.php           # create tables (if missing) + seed demo data
 *   php database/migrate.php --fresh   # drop everything and rebuild from scratch
 *
 * Works for both SQLite (default) and MySQL (DB_DRIVER=mysql). For MySQL it will
 * also CREATE DATABASE IF NOT EXISTS using the configured credentials.
 */

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit('Run this script from the command line: php database/migrate.php');
}

require __DIR__ . '/../app/bootstrap.php';

$driver = config('db.driver');
$fresh  = in_array('--fresh', $argv, true);

echo "HRMS migrate — driver: {$driver}\n";

// --- For MySQL, ensure the database exists ---------------------------------
if ($driver === 'mysql') {
    $dsn = sprintf('mysql:host=%s;port=%s;charset=%s', config('db.host'), config('db.port'), config('db.charset'));
    $root = new PDO($dsn, config('db.username'), config('db.password'), [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $db = config('db.database');
    $root->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Ensured database `{$db}` exists.\n";
}

$pdo = Database::connection();

// --- --fresh: drop tables ---------------------------------------------------
if ($fresh) {
    echo "Dropping existing tables...\n";
    if ($driver === 'mysql') {
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        foreach (['salaries', 'leaves', 'attendance', 'employees', 'users'] as $t) {
            $pdo->exec("DROP TABLE IF EXISTS {$t}");
        }
        $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    } else {
        foreach (['salaries', 'leaves', 'attendance', 'employees', 'users'] as $t) {
            $pdo->exec("DROP TABLE IF EXISTS {$t}");
        }
    }
}

// --- Load schema ------------------------------------------------------------
$schemaFile = $driver === 'mysql'
    ? ROOT_PATH . '/database/schema.sql'
    : ROOT_PATH . '/database/schema.sqlite.sql';

echo 'Loading schema: ' . basename($schemaFile) . "\n";
run_sql_file($pdo, $schemaFile);

// --- Seed -------------------------------------------------------------------
$already = Database::first('SELECT COUNT(*) AS c FROM employees');
if ((int) ($already['c'] ?? 0) > 0 && !$fresh) {
    echo "Data already present — skipping seed (use --fresh to reset).\n";
    echo "Done.\n";
    return;
}

echo "Seeding demo data...\n";
seed();
echo "Done.\n";
echo "\nLog in at /login with:\n";
echo "  Admin     →  admin@hrms.test    / password\n";
echo "  Employee  →  aarav@hrms.test    / password\n";

// ===========================================================================

function run_sql_file(PDO $pdo, string $file): void
{
    $sql = file_get_contents($file);
    // Strip full-line comments so empty fragments don't error.
    $sql = preg_replace('/^\s*--.*$/m', '', $sql);
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
        $pdo->exec($statement);
    }
}

function seed(): void
{
    // 1) HR admin account (no linked employee).
    User::create('HR Admin', 'admin@hrms.test', 'password', 'admin', null);

    // 2) Employees.
    $people = [
        ['Aarav Sharma',   'aarav@hrms.test',   'Engineering', 'Software Engineer',  60000, '2023-04-01'],
        ['Diya Patel',     'diya@hrms.test',    'Engineering', 'Senior Engineer',    85000, '2022-01-15'],
        ['Rohan Verma',    'rohan@hrms.test',   'Sales',       'Sales Executive',    40000, '2024-06-10'],
        ['Ananya Iyer',    'ananya@hrms.test',  'HR',          'HR Executive',       45000, '2023-09-01'],
        ['Kabir Singh',    'kabir@hrms.test',   'Finance',     'Accountant',         50000, '2021-11-20'],
    ];

    $employeeIds = [];
    foreach ($people as $i => $p) {
        [$name, $email, $dept, $desig, $salary, $joined] = $p;
        $id = Employee::create([
            'employee_code' => 'EMP' . str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
            'name'          => $name,
            'email'         => $email,
            'phone'         => '98' . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT),
            'department'    => $dept,
            'designation'   => $desig,
            'joining_date'  => $joined,
            'basic_salary'  => $salary,
            'status'        => 'active',
        ]);
        $employeeIds[] = $id;
    }

    // 3) A login for the first employee so you can try the employee role.
    User::create('Aarav Sharma', 'aarav@hrms.test', 'password', 'employee', $employeeIds[0]);

    // 4) Attendance for the current month up to today (a realistic mix).
    $month   = date('Y-m');
    $lastDay = (int) date('j'); // today's day-of-month
    foreach ($employeeIds as $idx => $empId) {
        for ($day = 1; $day <= $lastDay; $day++) {
            $date = sprintf('%s-%02d', $month, $day);
            $dow  = (int) date('N', strtotime($date)); // 6=Sat, 7=Sun
            if ($dow >= 6) {
                continue; // weekends off
            }
            // Leave TODAY open for the demo-login employee so you can try
            // a live check-in / check-out right after logging in as them.
            if ($day === $lastDay && $idx === 0) {
                continue;
            }
            // Sprinkle a couple of absences / half-days per employee.
            if ($day % 9 === ($idx % 9)) {
                insert_attendance($empId, $date, null, null, 0, 0, 'Absent');
            } elseif ($day % 11 === 0) {
                insert_attendance($empId, $date, '09:30:00', '13:30:00', 4, 0, 'Half-Day');
            } else {
                // 09:00 -> 18:00 = 9h worked, 1h overtime.
                insert_attendance($empId, $date, '09:00:00', '18:00:00', 9, 1, 'Present');
            }
        }
    }

    // 5) Leave requests: one pending, one already approved.
    LeaveRequest::create([
        'employee_id' => $employeeIds[2],
        'leave_type'  => 'Casual',
        'start_date'  => date('Y-m-d', strtotime('+3 days')),
        'end_date'    => date('Y-m-d', strtotime('+4 days')),
        'days'        => 2,
        'reason'      => 'Family function',
    ]);

    $lid = LeaveRequest::create([
        'employee_id' => $employeeIds[1],
        'leave_type'  => 'Sick',
        'start_date'  => date('Y-m-01'),
        'end_date'    => date('Y-m-01'),
        'days'        => 1,
        'reason'      => 'Fever',
    ]);
    LeaveRequest::setStatus($lid, 'Approved', 1);
    Attendance::mark($employeeIds[1], date('Y-m-01'), 'Leave');
}

function insert_attendance(int $empId, string $date, ?string $in, ?string $out, float $hours, float $ot, string $status): void
{
    Database::execute(
        'INSERT INTO attendance (employee_id, attendance_date, check_in, check_out, work_hours, overtime_hours, status)
         VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$empId, $date, $in, $out, $hours, $ot, $status]
    );
}
