<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class FormServiceRule
{
    private const TABLE = 'form_service_rules';

    public static function findByTemplate(int $formTemplateId): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE form_template_id = ? ORDER BY id ASC",
            [$formTemplateId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return Database::insert(self::TABLE, $data);
    }

    public static function delete(int $id): int
    {
        return Database::delete(self::TABLE, 'id = ?', [$id]);
    }
}
