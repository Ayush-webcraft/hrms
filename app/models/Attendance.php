<?php

declare(strict_types=1);

class Attendance
{
    /** Today's attendance row for an employee, if any. */
    public static function todayFor(int $employeeId): ?array
    {
        return Database::first(
            'SELECT * FROM attendance WHERE employee_id = ? AND attendance_date = ?',
            [$employeeId, date('Y-m-d')]
        );
    }

    public static function find(int $id): ?array
    {
        return Database::first('SELECT * FROM attendance WHERE id = ?', [$id]);
    }

    /** Record a check-in for today (creates the row, status Present). */
    public static function checkIn(int $employeeId): void
    {
        $today = date('Y-m-d');
        $now   = date('H:i:s');
        Database::execute(
            'INSERT INTO attendance (employee_id, attendance_date, check_in, status)
             VALUES (?, ?, ?, ?)',
            [$employeeId, $today, $now, 'Present']
        );
    }

    /** Record a check-out for today and compute worked + overtime hours. */
    public static function checkOut(int $employeeId): void
    {
        $row = self::todayFor($employeeId);
        if (!$row || empty($row['check_in'])) {
            return;
        }
        $now = date('H:i:s');

        $in  = strtotime($row['attendance_date'] . ' ' . $row['check_in']);
        $out = strtotime($row['attendance_date'] . ' ' . $now);
        $hours = max(0, ($out - $in) / 3600);

        $standard = (float) config('payroll.standard_work_hours');
        $overtime = max(0, $hours - $standard);

        Database::execute(
            'UPDATE attendance SET check_out = ?, work_hours = ?, overtime_hours = ? WHERE id = ?',
            [$now, round($hours, 2), round($overtime, 2), $row['id']]
        );
    }

    /**
     * Upsert a manual attendance status (Present/Absent/Leave/Half-Day)
     * for a given employee + date. Used by the admin and by leave approval.
     */
    public static function mark(int $employeeId, string $date, string $status): void
    {
        $existing = Database::first(
            'SELECT id FROM attendance WHERE employee_id = ? AND attendance_date = ?',
            [$employeeId, $date]
        );
        if ($existing) {
            Database::execute('UPDATE attendance SET status = ? WHERE id = ?', [$status, $existing['id']]);
        } else {
            Database::execute(
                'INSERT INTO attendance (employee_id, attendance_date, status) VALUES (?, ?, ?)',
                [$employeeId, $date, $status]
            );
        }
    }

    /** Monthly attendance rows for an employee. $month = 'YYYY-MM'. */
    public static function forMonth(int $employeeId, string $month): array
    {
        return Database::all(
            "SELECT * FROM attendance
             WHERE employee_id = ? AND substr(attendance_date, 1, 7) = ?
             ORDER BY attendance_date ASC",
            [$employeeId, $month]
        );
    }

    /** All attendance for a given date (admin daily view). */
    public static function forDate(string $date): array
    {
        return Database::all(
            'SELECT a.*, e.name, e.employee_code, e.department
             FROM attendance a
             JOIN employees e ON e.id = a.employee_id
             WHERE a.attendance_date = ?
             ORDER BY e.name ASC',
            [$date]
        );
    }

    /** Count distinct employees with a given status on a date. */
    public static function countByStatus(string $date, string $status): int
    {
        $row = Database::first(
            'SELECT COUNT(*) AS c FROM attendance WHERE attendance_date = ? AND status = ?',
            [$date, $status]
        );
        return (int) ($row['c'] ?? 0);
    }

    /**
     * Aggregate counts for a month, used by payroll.
     * Returns present/half/absent/leave day counts + total overtime hours.
     */
    public static function monthlySummary(int $employeeId, string $month): array
    {
        $rows = self::forMonth($employeeId, $month);
        $summary = [
            'present'        => 0,
            'half'           => 0,
            'absent'         => 0,
            'leave'          => 0,
            'overtime_hours' => 0.0,
        ];
        foreach ($rows as $r) {
            switch ($r['status']) {
                case 'Present':  $summary['present']++; break;
                case 'Half-Day': $summary['half']++;    break;
                case 'Absent':   $summary['absent']++;  break;
                case 'Leave':    $summary['leave']++;   break;
            }
            $summary['overtime_hours'] += (float) $r['overtime_hours'];
        }
        return $summary;
    }
}
