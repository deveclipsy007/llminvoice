<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class EmailModel
{
    private const TABLE = 'emails';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findByClientId(int $clientId): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE client_id = ? ORDER BY created_at DESC",
            [$clientId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['tracking_id'])) {
            $data['tracking_id'] = generate_token(16);
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function updateStatus(int $id, string $status): void
    {
        Database::update(
            self::TABLE,
            [
                'status'     => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }

    public static function markSent(int $id): void
    {
        Database::update(
            self::TABLE,
            [
                'status'     => 'sent',
                'sent_at'    => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }
}
