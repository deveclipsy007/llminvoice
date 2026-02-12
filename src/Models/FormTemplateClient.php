<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class FormTemplateClient
{
    private const TABLE = 'form_template_clients';

    public static function link(int $formId, int $clientId): int
    {
        return Database::insert(self::TABLE, [
            'form_template_id' => $formId,
            'client_id'        => $clientId,
            'created_at'       => date('Y-m-d H:i:s'),
        ]);
    }

    public static function findByClient(int $clientId): array
    {
        return Database::fetchAll(
            "SELECT ftc.*, ft.name AS template_name
             FROM " . self::TABLE . " ftc
             LEFT JOIN form_templates ft ON ft.id = ftc.form_template_id
             WHERE ftc.client_id = ?
             ORDER BY ftc.created_at DESC",
            [$clientId]
        );
    }

    public static function findByTemplate(int $templateId): array
    {
        return Database::fetchAll(
            "SELECT ftc.*, c.name AS client_name, c.email AS client_email
             FROM " . self::TABLE . " ftc
             LEFT JOIN clients c ON c.id = ftc.client_id
             WHERE ftc.form_template_id = ?
             ORDER BY ftc.created_at DESC",
            [$templateId]
        );
    }
}
