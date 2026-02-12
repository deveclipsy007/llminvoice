<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class PublicFormResponse
{
    private const TABLE = 'public_form_responses';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findByUuid(string $uuid): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE uuid = ?", [$uuid]);
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
        if (!isset($data['uuid'])) {
            $data['uuid'] = generate_uuid();
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function updateAnalysis(int $id, array $analysis, array $services): void
    {
        Database::update(
            self::TABLE,
            [
                'ai_analysis'          => json_encode($analysis),
                'recommended_services' => json_encode($services),
                'updated_at'           => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
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

    public static function count(): int
    {
        return Database::count(self::TABLE);
    }

    public static function countByStatus(): array
    {
        return Database::fetchAll(
            "SELECT status, COUNT(*) AS total FROM " . self::TABLE . " GROUP BY status"
        );
    }
}
