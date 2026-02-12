<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Client
{
    private const TABLE = 'clients';

    public static function findById(int $id): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE id = ?", [$id]);
    }

    public static function findByUuid(string $uuid): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE uuid = ?", [$uuid]);
    }

    public static function findByFormToken(string $token): ?array
    {
        return Database::fetch("SELECT * FROM " . self::TABLE . " WHERE form_token = ?", [$token]);
    }

    public static function allByColumn(int $columnId): array
    {
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE pipeline_column_id = ? AND archived_at IS NULL ORDER BY position_in_column ASC",
            [$columnId]
        );
    }

    public static function create(array $data): int
    {
        if (!isset($data['uuid'])) {
            $data['uuid'] = generate_uuid();
        }

        if (!isset($data['form_token'])) {
            $data['form_token'] = generate_token();
        }

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

    public static function updateColumn(int $id, int $columnId, int $position): void
    {
        Database::update(
            self::TABLE,
            [
                'pipeline_column_id' => $columnId,
                'position_in_column' => $position,
                'updated_at'         => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }

    public static function updateTemperature(int $id, string $temp): void
    {
        Database::update(
            self::TABLE,
            [
                'temperature' => $temp,
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            'id = ?',
            [$id]
        );
    }

    public static function archive(int $id): void
    {
        Database::update(
            self::TABLE,
            ['archived_at' => date('Y-m-d H:i:s')],
            'id = ?',
            [$id]
        );
    }

    public static function search(string $q): array
    {
        $like = '%' . $q . '%';
        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE (name LIKE ? OR email LIKE ? OR company LIKE ?) AND archived_at IS NULL ORDER BY name ASC",
            [$like, $like, $like]
        );
    }

    public static function count(string $where = '1=1', array $params = []): int
    {
        return Database::count(self::TABLE, $where, $params);
    }

    public static function countByColumn(): array
    {
        return Database::fetchAll(
            "SELECT pipeline_column_id, COUNT(*) AS total
             FROM " . self::TABLE . "
             WHERE archived_at IS NULL
             GROUP BY pipeline_column_id"
        );
    }

    public static function allPaginated(int $page, int $perPage, array $filters = []): array
    {
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['column_id'])) {
            $where[] = 'pipeline_column_id = ?';
            $params[] = $filters['column_id'];
        }

        if (!empty($filters['temperature'])) {
            $where[] = 'temperature = ?';
            $params[] = $filters['temperature'];
        }

        if (!empty($filters['search'])) {
            $where[] = '(name LIKE ? OR email LIKE ? OR company LIKE ?)';
            $like = '%' . $filters['search'] . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if (empty($filters['include_archived'])) {
            $where[] = 'archived_at IS NULL';
        }

        $whereStr = implode(' AND ', $where);
        $offset = ($page - 1) * $perPage;

        $params[] = $perPage;
        $params[] = $offset;

        return Database::fetchAll(
            "SELECT * FROM " . self::TABLE . " WHERE {$whereStr} ORDER BY created_at DESC LIMIT ? OFFSET ?",
            $params
        );
    }
}
