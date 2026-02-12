<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

class Branding
{
    private const TABLE = 'branding';

    private const DEFAULTS = [
        'company_name'    => '',
        'logo_path'       => '',
        'favicon_path'    => '',
        'primary_color'   => '#4F46E5',
        'secondary_color' => '#7C3AED',
        'accent_color'    => '#F59E0B',
        'font_family'     => 'Inter, sans-serif',
        'custom_css'      => '',
    ];

    public static function get(): array
    {
        $row = Database::fetch("SELECT * FROM " . self::TABLE . " ORDER BY id ASC LIMIT 1");

        if ($row === null) {
            return self::DEFAULTS;
        }

        return array_merge(self::DEFAULTS, $row);
    }

    public static function update(array $data): void
    {
        $existing = Database::fetch("SELECT id FROM " . self::TABLE . " ORDER BY id ASC LIMIT 1");

        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($existing) {
            Database::update(self::TABLE, $data, 'id = ?', [$existing['id']]);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            Database::insert(self::TABLE, $data);
        }
    }
}
