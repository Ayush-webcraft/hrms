<?php

declare(strict_types=1);

/**
 * Leave applications. (Named LeaveRequest because `leave` / `Leave` are awkward;
 * the underlying table is `leaves`.)
 */
class LeaveRequest
{
    public static function find(int $id): ?array
    {
        return Database::first('SELECT * FROM leaves WHERE id = ?', [$id]);
    }

    /** All leave requests (admin view), newest first, with employee info. */
    public static function all(): array
    {
        return Database::all(
            'SELECT l.*, e.name, e.employee_code
             FROM leaves l
             JOIN employees e ON e.id = l.employee_id
             ORDER BY l.applied_at DESC, l.id DESC'
        );
    }

    /** Leave requests for one employee. */
    public static function forEmployee(int $employeeId): array
    {
        return Database::all(
            'SELECT * FROM leaves WHERE employee_id = ? ORDER BY applied_at DESC, id DESC',
            [$employeeId]
        );
    }

    public static function create(array $data): int
    {
        Database::execute(
            'INSERT INTO leaves (employee_id, leave_type, start_date, end_date, days, reason, status)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $data['employee_id'],
                $data['leave_type'],
                $data['start_date'],
                $data['end_date'],
                $data['days'],
                $data['reason'],
                'Pending',
            ]
        );
        return (int) Database::lastInsertId();
    }

    public static function setStatus(int $id, string $status, int $reviewerId): void
    {
        Database::execute(
            'UPDATE leaves SET status = ?, reviewed_by = ?, reviewed_at = ? WHERE id = ?',
            [$status, $reviewerId, date('Y-m-d H:i:s'), $id]
        );
    }

    public static function pendingCount(): int
    {
        $row = Database::first("SELECT COUNT(*) AS c FROM leaves WHERE status = 'Pending'");
        return (int) ($row['c'] ?? 0);
    }

    /** Approved leave days taken this calendar year (for balance tracking). */
    public static function approvedDaysThisYear(int $employeeId): int
    {
        $row = Database::first(
            "SELECT COALESCE(SUM(days), 0) AS d
             FROM leaves
             WHERE employee_id = ? AND status = 'Approved'
               AND substr(start_date, 1, 4) = ?",
            [$employeeId, date('Y')]
        );
        return (int) ($row['d'] ?? 0);
    }

    /** Inclusive number of days between two dates. */
    public static function daysBetween(string $start, string $end): int
    {
        $s = strtotime($start);
        $e = strtotime($end);
        if ($s === false || $e === false || $e < $s) {
            return 0;
        }
        return (int) floor(($e - $s) / 86400) + 1;
    }
}
