<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class ProposalVersion
{
    private const TABLE = 'proposal_versions';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findByProposal(int $proposalId): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE proposal_id = ? ORDER BY version_number DESC",
            [$proposalId]
        );
    }

    public static function latestByProposal(int $proposalId): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . self::TABLE . " WHERE proposal_id = ? ORDER BY version_number DESC LIMIT 1",
            [$proposalId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['version_number'])) {
            $data['version_number'] = self::getNextVersionNumber((int) $data['proposal_id']);
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function getNextVersionNumber(int $proposalId): int
    {
        $max = Database::fetchColumn(
            "SELECT COALESCE(MAX(version_number), 0) FROM " . self::TABLE . " WHERE proposal_id = ?",
            [$proposalId]
        );

        return ((int) $max) + 1;
    }
}
