<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class FormResponse
{
    private const TABLE = 'form_responses';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findByClientId(int $clientId): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . self::TABLE . " WHERE client_id = ? ORDER BY created_at DESC LIMIT 1",
            [$clientId]
        );
    }

    public static function findByClientAndTemplate(int $clientId, int $templateId): ?array
    {
        return Database::fetch(
            "SELECT * FROM " . self::TABLE . " WHERE client_id = ? AND form_template_id = ?",
            [$clientId, $templateId]
        );
    }

    public static function create(array $data): int
    {
        if (isset($data['responses']) && is_array($data['responses'])) {
            $data['responses'] = json_encode($data['responses']);
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function update(int $id, array $data): int
    {
        if (isset($data['responses']) && is_array($data['responses'])) {
            $data['responses'] = json_encode($data['responses']);
        }

        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return Database::update(self::TABLE, $data, 'id = ?', [$id]);
    }

    public static function updateCompletion(int $id, float $pct): void
    {
        Database::update(
            self::TABLE,
            [
                'completion_pct' => $pct,
                'updated_at'     => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }

    public static function markSubmitted(int $id): void
    {
        Database::update(
            self::TABLE,
            [
                'status'       => 'submitted',
                'submitted_at' => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }

    public static function getResponses(int $id): array
    {
        $json = Database::fetchColumn(
            "SELECT responses FROM " . self::TABLE . " WHERE id = ?",
            [$id]
        );

        if ($json === null || $json === false || $json === '') {
            return [];
        }

        $decoded = json_decode((string) $json, true);

        return is_array($decoded) ? $decoded : [];
    }
}
