<?php

declare(strict_types=1);

class Employee
{
    public static function all(string $search = ''): array
    {
        if ($search !== '') {
            $like = '%' . $search . '%';
            return Database::all(
                'SELECT * FROM employees
                 WHERE name LIKE ? OR email LIKE ? OR employee_code LIKE ? OR department LIKE ?
                 ORDER BY id DESC',
                [$like, $like, $like, $like]
            );
        }
        return Database::all('SELECT * FROM employees ORDER BY id DESC');
    }

    public static function find(int $id): ?array
    {
        return Database::first('SELECT * FROM employees WHERE id = ?', [$id]);
    }

    public static function create(array $data): int
    {
        Database::execute(
            'INSERT INTO employees
                (employee_code, name, email, phone, department, designation, joining_date, basic_salary, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                $data['employee_code'],
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['department'],
                $data['designation'],
                $data['joining_date'],
                $data['basic_salary'],
                $data['status'] ?? 'active',
            ]
        );
        return (int) Database::lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        Database::execute(
            'UPDATE employees SET
                name = ?, email = ?, phone = ?, department = ?,
                designation = ?, joining_date = ?, basic_salary = ?, status = ?
             WHERE id = ?',
            [
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['department'],
                $data['designation'],
                $data['joining_date'],
                $data['basic_salary'],
                $data['status'],
                $id,
            ]
        );
    }

    public static function delete(int $id): void
    {
        Database::execute('DELETE FROM employees WHERE id = ?', [$id]);
        // Also remove the linked login account, if any.
        Database::execute('DELETE FROM users WHERE employee_id = ?', [$id]);
    }

    public static function count(): int
    {
        $row = Database::first('SELECT COUNT(*) AS c FROM employees WHERE status = ?', ['active']);
        return (int) ($row['c'] ?? 0);
    }

    /** Generate the next employee code, e.g. EMP0007. */
    public static function nextCode(): string
    {
        $row = Database::first('SELECT COUNT(*) AS c FROM employees');
        $next = ((int) ($row['c'] ?? 0)) + 1;
        return 'EMP' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
