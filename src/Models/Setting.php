<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Setting
{
    private const TABLE = 'settings';

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Database::fetchColumn(
            "SELECT value FROM " . self::TABLE . " WHERE key = ?",
            [$key]
        );

        if ($value === null || $value === false) {
            return $default;
        }

        return $value;
    }

    public static function set(string $key, mixed $value, string $group = 'general'): void
    {
        $existing = Database::fetch(
            "SELECT id FROM " . self::TABLE . " WHERE key = ?",
            [$key]
        );

        if ($existing) {
            Database::update(
                self::TABLE,
                [
                    'value'      => (string) $value,
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                'key = ?',
                [$key]
            );
        } else {
            Database::insert(self::TABLE, [
                'key'        => $key,
                'value'      => (string) $value,
                'group_name' => $group,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public static function allByGroup(string $group): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE group_name = ? ORDER BY key ASC",
            [$group]
        );
    }

    public static function bulkUpdate(array $settings): void
    {
        Database::beginTransaction();

        try {
            foreach ($settings as $key => $value) {
                $group = 'general';

                // Allow passing ['key' => ['value' => ..., 'group' => ...]]
                if (is_array($value)) {
                    $group = $value['group'] ?? 'general';
                    $value = $value['value'] ?? '';
                }

                self::set($key, $value, $group);
            }

            Database::commit();
        } catch (\Throwable $e) {
            Database::rollback();
            throw $e;
        }
    }
}
