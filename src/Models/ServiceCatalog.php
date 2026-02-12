<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class ServiceCatalog
{
    private const TABLE = 'service_catalog';

    public static function all(): array
    {
        return Database::fetchAll("SELECT * FROM " . self::TABLE . " ORDER BY category ASC, name ASC");
    }

    public static function allActive(): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE is_active = 1 ORDER BY category ASC, name ASC"
        );
    }

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
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

    public static function allByCategory(): array
    {
        $services = self::allActive();
        $grouped = [];

        foreach ($services as $service) {
            $category = $service['category'] ?? 'Uncategorized';
            $grouped[$category][] = $service;
        }

        return $grouped;
    }

    public static function import(array $services): int
    {
        $count = 0;

        Database::beginTransaction();

        try {
            foreach ($services as $service) {
                if (!isset($service['created_at'])) {
                    $service['created_at'] = date('Y-m-d H:i:s');
                }

                Database::insert(self::TABLE, $service);
                $count++;
            }

            Database::commit();
        } catch (\Throwable $e) {
            Database::rollback();
            throw $e;
        }

        return $count;
    }
}
