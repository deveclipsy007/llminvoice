<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class PipelineColumn
{
    private const TABLE = 'pipeline_columns';

    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . self::TABLE . " ORDER BY sort_order ASC");
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE slug = ?", [$slug]);
    }

    public static function allWithClientCounts(): array
    {
        return Database::fetchAll(
            "SELECT pc.*, COUNT(c.id) AS client_count
             FROM " . self::TABLE . " pc
             LEFT JOIN clients c ON c.pipeline_column_id = pc.id AND c.archived_at IS NULL
             GROUP BY pc.id
             ORDER BY pc.sort_order ASC"
        );
    }

    /**
     * Return the column name based on the current application locale.
     * Falls back to name_en if the localized name is not available.
     */
    public static function columnName(array $column): string
    {
        $locale = \App\Core\App::locale();
        $key = 'name_' . $locale;

        return $column[$key] ?? $column['name_en'] ?? $column['name'] ?? '';
    }
}
