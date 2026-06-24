<?php

declare(strict_types=1);

/**
 * Thin PDO wrapper exposing a shared connection plus a few query helpers.
 * Supports both SQLite (zero-config default) and MySQL (per the project spec).
 */
class Database
{
    private static ?PDO $pdo = null;

    /** Get the shared PDO connection, creating it on first use. */
    public static function connection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $driver = config('db.driver');
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        if ($driver === 'mysql') {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                config('db.host'),
                config('db.port'),
                config('db.database'),
                config('db.charset')
            );
            self::$pdo = new PDO($dsn, config('db.username'), config('db.password'), $options);
        } else {
            $path = config('db.sqlite_path');
            if (!is_dir(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }
            self::$pdo = new PDO('sqlite:' . $path, null, null, $options);
            self::$pdo->exec('PRAGMA foreign_keys = ON');
        }

        return self::$pdo;
    }

    /** Run a prepared statement and return it. */
    public static function run(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /** Fetch a single row (or null). */
    public static function first(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    /** Fetch all rows. */
    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    /** Insert/update/delete and return affected row count. */
    public static function execute(string $sql, array $params = []): int
    {
        return self::run($sql, $params)->rowCount();
    }

    /** Last inserted id. */
    public static function lastInsertId(): string
    {
        return self::connection()->lastInsertId();
    }
}
