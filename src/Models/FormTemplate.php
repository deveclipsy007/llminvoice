<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class FormTemplate
{
    private const TABLE = 'form_templates';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findDefault(): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE is_default = 1 LIMIT 1");
    }

    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . self::TABLE . " ORDER BY name ASC");
    }

    public static function create(array $data): int
    {
        if (isset($data['structure']) && is_array($data['structure'])) {
            $data['structure'] = json_encode($data['structure']);
        }

        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function update(int $id, array $data): int
    {
        if (isset($data['structure']) && is_array($data['structure'])) {
            $data['structure'] = json_encode($data['structure']);
        }

        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return Database::update(self::TABLE, $data, 'id = ?', [$id]);
    }

    public static function getStructure(int $id): array
    {
        $json = Database::fetchColumn(
            "SELECT structure FROM " . self::TABLE . " WHERE id = ?",
            [$id]
        );

        if ($json === null || $json === false || $json === '') {
            return [];
        }

        $decoded = json_decode((string) $json, true);

        return is_array($decoded) ? $decoded : [];
    }
}
