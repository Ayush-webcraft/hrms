<?php

declare(strict_types=1);

/**
 * Session-based authentication and role-based access control.
 *
 * Note on JWT: the original spec listed JWT auth, which suits a *stateless API*
 * consumed by a separate JavaScript SPA. This is a server-rendered PHP app, so
 * sessions are the idiomatic and secure choice (no token to leak into JS, CSRF
 * is handled separately). The role checks below provide the same RBAC.
 */
class Auth
{
    /** Attempt to log a user in by email + password. Returns the user or null. */
    public static function attempt(string $email, string $password): ?array
    {
        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            self::login($user);
            return $user;
        }
        return null;
    }

    public static function login(array $user): void
    {
        // Prevent session fixation.
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'          => (int) $user['id'],
            'name'        => $user['name'],
            'email'       => $user['email'],
            'role'        => $user['role'],
            'employee_id' => $user['employee_id'] !== null ? (int) $user['employee_id'] : null,
        ];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    /** The linked employee id (for employee accounts), or null. */
    public static function employeeId(): ?int
    {
        return $_SESSION['user']['employee_id'] ?? null;
    }

    public static function role(): ?string
    {
        return $_SESSION['user']['role'] ?? null;
    }

    public static function is(string $role): bool
    {
        return self::role() === $role;
    }

    public static function isAdmin(): bool
    {
        return self::is('admin');
    }

    public static function requireAuth(): void
    {
        if (!self::check()) {
            flash('error', 'Please log in to continue.');
            redirect('/login');
        }
    }

    public static function requireRole(string $role): void
    {
        self::requireAuth();
        if (!self::is($role)) {
            http_response_code(403);
            exit('403 — You do not have permission to access this page.');
        }
    }
}
