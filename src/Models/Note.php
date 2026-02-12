<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Note
{
    private const TABLE = 'notes';

    public static function findByClientId(int $clientId, int $limit = 20): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE client_id = ? ORDER BY created_at DESC LIMIT ?",
            [$clientId, $limit]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function count(int $clientId): int
    {
        return Database::count(self::TABLE, 'client_id = ?', [$clientId]);
    }
}
