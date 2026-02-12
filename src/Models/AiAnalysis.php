<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class AiAnalysis
{
    private const TABLE = 'ai_analyses';

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

    public static function latestByClient(int $clientId): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . self::TABLE . " WHERE client_id = ? ORDER BY created_at DESC LIMIT 1",
            [$clientId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function updateStatus(int $id, string $status, ?string $error = null): void
    {
        $data = [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($error !== null) {
            $data['error_message'] = $error;
        }

        Database::update(self::TABLE, $data, 'id = ?', [$id]);
    }

    public static function updateResult(int $id, array $result): void
    {
        $data = [
            'status'                => 'completed',
            'updated_at'            => date('Y-m-d H:i:s'),
            'completed_at'          => date('Y-m-d H:i:s'),
        ];

        if (isset($result['summary'])) {
            $data['summary'] = $result['summary'];
        }
        if (isset($result['recommended_services'])) {
            $data['recommended_services'] = is_array($result['recommended_services'])
                ? json_encode($result['recommended_services'])
                : $result['recommended_services'];
        }
        if (isset($result['estimated_budget_min'])) {
            $data['estimated_budget_min'] = $result['estimated_budget_min'];
        }
        if (isset($result['estimated_budget_max'])) {
            $data['estimated_budget_max'] = $result['estimated_budget_max'];
        }
        if (isset($result['complexity_score'])) {
            $data['complexity_score'] = $result['complexity_score'];
        }
        if (isset($result['suggested_timeline'])) {
            $data['suggested_timeline'] = $result['suggested_timeline'];
        }
        if (isset($result['cost'])) {
            $data['cost'] = $result['cost'];
        }
        if (isset($result['tokens_used'])) {
            $data['tokens_used'] = $result['tokens_used'];
        }
        if (isset($result['raw_response'])) {
            $data['raw_response'] = is_array($result['raw_response'])
                ? json_encode($result['raw_response'])
                : $result['raw_response'];
        }

        Database::update(self::TABLE, $data, 'id = ?', [$id]);
    }

    public static function countThisMonth(): int
    {
        $startOfMonth = date('Y-m-01 00:00:00');
        return Database::count(self::TABLE, 'created_at >= ?', [$startOfMonth]);
    }
}
