<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class User
{
    private const TABLE = 'users';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findByEmail(string $email): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE email = ?", [$email]);
    }

    public static function all(int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );
    }

    public static function create(array $data): int
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function update(int $id, array $data): int
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return Database::update(self::TABLE, $data, 'id = ?', [$id]);
    }

    public static function updateLastLogin(int $id): void
    {
        Database::update(self::TABLE, ['last_login_at' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
    }

    public static function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public static function count(): int
    {
        return Database::count(self::TABLE);
    }

    public static function allActive(): array
    {
        return Database::fetchAll("SELECT * FROM " . self::TABLE . " WHERE is_active = 1 ORDER BY name ASC");
    }
}
