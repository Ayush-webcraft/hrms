<?php

declare(strict_types=1);

class Salary
{
    public static function find(int $id): ?array
    {
        return Database::first('SELECT * FROM salaries WHERE id = ?', [$id]);
    }

    /** All payslips for a month, with employee info (admin payroll list). */
    public static function forMonth(string $month): array
    {
        return Database::all(
            'SELECT s.*, e.name, e.employee_code, e.department, e.designation
             FROM salaries s
             JOIN employees e ON e.id = s.employee_id
             WHERE s.month = ?
             ORDER BY e.name ASC',
            [$month]
        );
    }

    public static function forEmployee(int $employeeId): array
    {
        return Database::all(
            'SELECT * FROM salaries WHERE employee_id = ? ORDER BY month DESC',
            [$employeeId]
        );
    }

    public static function existsFor(int $employeeId, string $month): bool
    {
        return Database::first(
            'SELECT id FROM salaries WHERE employee_id = ? AND month = ?',
            [$employeeId, $month]
        ) !== null;
    }

    /** Total net payroll paid out in a month. */
    public static function totalForMonth(string $month): float
    {
        $row = Database::first('SELECT COALESCE(SUM(net_salary), 0) AS t FROM salaries WHERE month = ?', [$month]);
        return (float) ($row['t'] ?? 0);
    }

    /**
     * Compute a payslip for an employee + month from their attendance.
     *
     *   per_day      = basic_salary / working_days   (working_days = days in month)
     *   deduction    = (absent + 0.5*half_days) * per_day   (approved leave is paid)
     *   overtime_pay = overtime_hours * (per_day / std_hours) * multiplier
     *   net          = basic - deduction + overtime_pay
     *
     * @return array the computed (un-persisted) salary row.
     */
    public static function compute(array $employee, string $month): array
    {
        $workingDays = (int) date('t', strtotime($month . '-01')); // days in month
        $basic       = (float) $employee['basic_salary'];
        $perDay      = $workingDays > 0 ? $basic / $workingDays : 0.0;

        $summary = Attendance::monthlySummary((int) $employee['id'], $month);

        $absentEquivalent = $summary['absent'] + (0.5 * $summary['half']);
        $deductions       = $absentEquivalent * $perDay;

        $stdHours   = (float) config('payroll.standard_work_hours');
        $multiplier = (float) config('payroll.overtime_multiplier');
        $hourlyRate = $stdHours > 0 ? ($perDay / $stdHours) : 0.0;
        $overtimePay = $summary['overtime_hours'] * $hourlyRate * $multiplier;

        $net = $basic - $deductions + $overtimePay;

        return [
            'employee_id'     => (int) $employee['id'],
            'month'           => $month,
            'working_days'    => $workingDays,
            'present_days'    => $summary['present'],
            'absent_days'     => $summary['absent'],
            'leave_days'      => $summary['leave'],
            'half_days'       => $summary['half'],
            'per_day_salary'  => round($perDay, 2),
            'basic_salary'    => round($basic, 2),
            'overtime_hours'  => round($summary['overtime_hours'], 2),
            'overtime_amount' => round($overtimePay, 2),
            'deductions'      => round($deductions, 2),
            'net_salary'      => round($net, 2),
        ];
    }

    /** Insert or replace a payslip row for the employee + month. */
    public static function save(array $s): void
    {
        Database::execute(
            'DELETE FROM salaries WHERE employee_id = ? AND month = ?',
            [$s['employee_id'], $s['month']]
        );
        Database::execute(
            'INSERT INTO salaries
                (employee_id, month, working_days, present_days, absent_days, leave_days,
                 half_days, per_day_salary, basic_salary, overtime_hours, overtime_amount,
                 deductions, net_salary)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $s['employee_id'], $s['month'], $s['working_days'], $s['present_days'],
                $s['absent_days'], $s['leave_days'], $s['half_days'], $s['per_day_salary'],
                $s['basic_salary'], $s['overtime_hours'], $s['overtime_amount'],
                $s['deductions'], $s['net_salary'],
            ]
        );
    }
}
