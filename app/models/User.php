<?php

declare(strict_types=1);

class User
{
    public static function findByEmail(string $email): ?array
    {
        return Database::first('SELECT * FROM users WHERE email = ?', [$email]);
    }

    public static function find(int $id): ?array
    {
        return Database::first('SELECT * FROM users WHERE id = ?', [$id]);
    }

    /** Create a login account. Password is hashed here. */
    public static function create(string $name, string $email, string $password, string $role = 'employee', ?int $employeeId = null): int
    {
        Database::execute(
            'INSERT INTO users (name, email, password, role, employee_id) VALUES (?, ?, ?, ?, ?)',
            [$name, $email, password_hash($password, PASSWORD_DEFAULT), $role, $employeeId]
        );
        return (int) Database::lastInsertId();
    }

    public static function emailExists(string $email): bool
    {
        return Database::first('SELECT id FROM users WHERE email = ?', [$email]) !== null;
    }
}
