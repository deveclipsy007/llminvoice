<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class PipelineTransitionRule
{
    private const TABLE = 'pipeline_transition_rules';

    public static function getRules(int $fromColumnId, int $toColumnId): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE from_column_id = ? AND to_column_id = ?",
            [$fromColumnId, $toColumnId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function allByColumn(int $columnId): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE from_column_id = ? OR to_column_id = ?",
            [$columnId, $columnId]
        );
    }
}
