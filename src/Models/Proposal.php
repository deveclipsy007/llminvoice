<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Proposal
{
    private const TABLE = 'proposals';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findByUuid(string $uuid): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE uuid = ?", [$uuid]);
    }

    public static function findByClientId(int $clientId): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . self::TABLE . " WHERE client_id = ? ORDER BY created_at DESC LIMIT 1",
            [$clientId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['uuid'])) {
            $data['uuid'] = generate_uuid();
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function updateStatus(int $id, string $status, ?array $extra = []): void
    {
        $data = [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($extra)) {
            $data = array_merge($data, $extra);
        }

        Database::update(self::TABLE, $data, 'id = ?', [$id]);
    }

    public static function updateCurrentVersion(int $id, int $versionId): void
    {
        Database::update(
            self::TABLE,
            [
                'current_version_id' => $versionId,
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }

    public static function countByStatus(): array
    {
        return Database::fetchAll(
            "SELECT status, COUNT(*) AS total FROM " . self::TABLE . " GROUP BY status"
        );
    }

    public static function totalAcceptedValue(): float
    {
        $result = Database::fetchColumn(
            "SELECT COALESCE(SUM(pv.total_price), 0)
             FROM " . self::TABLE . " p
             INNER JOIN proposal_versions pv ON pv.id = p.current_version_id
             WHERE p.status = 'accepted'"
        );

        return (float) $result;
    }
}
