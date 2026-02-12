<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = config('database');
            $connection = $config['connection'] ?? 'sqlite';

            if ($connection === 'sqlite') {
                $dbPath = $config['sqlite']['path'] ?? App::basePath('database/database.sqlite');
                $dir = dirname($dbPath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0775, true);
                }
                if (!file_exists($dbPath)) {
                    touch($dbPath);
                }
                $dsn = 'sqlite:' . $dbPath;
                self::$instance = new PDO($dsn, null, null, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
                // SQLite optimizations
                self::$instance->exec('PRAGMA journal_mode = WAL');
                self::$instance->exec('PRAGMA foreign_keys = ON');
                self::$instance->exec('PRAGMA busy_timeout = 5000');
            } else {
                $mysql = $config['mysql'] ?? [];
                $dsn = sprintf(
                    'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                    $mysql['host'] ?? '127.0.0.1',
                    $mysql['port'] ?? '3306',
                    $mysql['database'] ?? 'llminvoice',
                    $mysql['charset'] ?? 'utf8mb4'
                );
                self::$instance = new PDO($dsn, $mysql['username'] ?? 'root', $mysql['password'] ?? '', [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            }
        }

        return self::$instance;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetchColumn(string $sql, array $params = [], int $column = 0): mixed
    {
        return self::query($sql, $params)->fetchColumn($column);
    }

    public static function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        self::query($sql, array_values($data));

        return (int) self::getInstance()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $sets = [];
        $values = [];
        foreach ($data as $column => $value) {
            $sets[] = "{$column} = ?";
            $values[] = $value;
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE {$where}";
        $stmt = self::query($sql, array_merge($values, $whereParams));

        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $whereParams = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = self::query($sql, $whereParams);

        return $stmt->rowCount();
    }

    public static function count(string $table, string $where = '1=1', array $params = []): int
    {
        return (int) self::fetchColumn("SELECT COUNT(*) FROM {$table} WHERE {$where}", $params);
    }

    public static function beginTransaction(): void
    {
        self::getInstance()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getInstance()->commit();
    }

    public static function rollback(): void
    {
        self::getInstance()->rollBack();
    }

    public static function lastInsertId(): int
    {
        return (int) self::getInstance()->lastInsertId();
    }

    /** Reset connection (useful for testing) */
    public static function reset(): void
    {
        self::$instance = null;
    }
}
