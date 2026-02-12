<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class AuditLog
{
    private const TABLE = 'audit_logs';

    public static function all(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        return Database::fetchAll(
            "SELECT al.*, u.name AS user_name
             FROM " . self::TABLE . " al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC
             LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function findByEntity(string $type, int $id): array
    {
        return Database::fetchAll(
            "SELECT al.*, u.name AS user_name
             FROM " . self::TABLE . " al
             LEFT JOIN users u ON u.id = al.user_id
             WHERE al.entity_type = ? AND al.entity_id = ?
             ORDER BY al.created_at DESC",
            [$type, $id]
        );
    }

    public static function count(): int
    {
        return Database::count(self::TABLE);
    }
}
