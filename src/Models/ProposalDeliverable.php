<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class ProposalDeliverable
{
    private const TABLE = 'proposal_deliverables';

    public static function findByPhase(int $phaseId): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE proposal_phase_id = ? ORDER BY sort_order ASC",
            [$phaseId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function update(int $id, array $data): int
    {
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return Database::update(self::TABLE, $data, 'id = ?', [$id]);
    }

    public static function delete(int $id): int
    {
        return Database::delete(self::TABLE, 'id = ?', [$id]);
    }
}
