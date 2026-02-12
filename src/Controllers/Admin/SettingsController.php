<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Database, Session};

class SettingsController
{
    /**
     * General settings page.
     */
    public function general(Request $request): Response
    {
        $settings = Database::fetchAll("SELECT * FROM settings ORDER BY setting_key");
        $settingsMap = [];
        foreach ($settings as $s) {
            $settingsMap[$s['setting_key']] = $s['setting_value'];
        }

        return Response::view('pages/admin/settings-general', [
            'pageTitle' => __('settings_title'),
            'settings'  => $settingsMap,
        ], 200, 'admin');
    }

    /**
     * Save general settings.
     */
    public function saveGeneral(Request $request): Response
    {
        $keys = ['app_name', 'timezone', 'default_locale', 'default_currency', 'proposal_validity_days', 'ai_primary_provider'];
        foreach ($keys as $key) {
            $value = $request->input($key, '');
            $existing = Database::fetch("SELECT id FROM settings WHERE setting_key = ?", [$key]);
            if ($existing) {
                Database::update('settings', (int)$existing['id'], ['setting_value' => $value, 'updated_at' => date('Y-m-d H:i:s')]);
            } else {
                Database::insert('settings', ['setting_key' => $key, 'setting_value' => $value, 'created_at' => date('Y-m-d H:i:s')]);
            }
        }

        flash('success', __('settings_saved'));
        return Response::redirect('/admin/settings');
    }

    /**
     * Service catalog page.
     */
    public function services(Request $request): Response
    {
        $services = Database::fetchAll("SELECT * FROM service_catalog ORDER BY category, name");

        return Response::view('pages/admin/settings-services', [
            'pageTitle' => __('settings_services'),
            'services'  => $services,
        ], 200, 'admin');
    }

    /**
     * Save service catalog.
     */
    public function saveService(Request $request): Response
    {
        $id = $request->input('id');
        $data = $request->only(['name', 'category', 'description', 'base_price_min', 'base_price_max', 'typical_duration_days', 'technical_difficulty', 'is_active']);
        $data['updated_at'] = date('Y-m-d H:i:s');

        if ($id) {
            Database::update('service_catalog', (int)$id, $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            Database::insert('service_catalog', $data);
        }

        flash('success', __('settings_saved'));
        return Response::redirect('/admin/settings/services');
    }

    /**
     * Branding settings page.
     */
    public function branding(Request $request): Response
    {
        $branding = Database::fetch("SELECT * FROM branding LIMIT 1");

        return Response::view('pages/admin/settings-branding', [
            'pageTitle' => __('settings_branding'),
            'branding'  => $branding ?? [],
        ], 200, 'admin');
    }

    /**
     * Save branding settings.
     */
    public function saveBranding(Request $request): Response
    {
        $data = $request->only(['company_name', 'primary_color', 'secondary_color', 'email_footer_text', 'proposal_header_text']);
        $data['updated_at'] = date('Y-m-d H:i:s');

        $existing = Database::fetch("SELECT id FROM branding LIMIT 1");
        if ($existing) {
            Database::update('branding', (int)$existing['id'], $data);
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            Database::insert('branding', $data);
        }

        // Handle logo upload
        if (isset($_FILES['logo_dark']) && $_FILES['logo_dark']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = \App\Core\App::basePath() . '/public/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = pathinfo($_FILES['logo_dark']['name'], PATHINFO_EXTENSION);
            $filename = 'logo_dark_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['logo_dark']['tmp_name'], $uploadDir . $filename);

            $brandId = $existing ? (int)$existing['id'] : (int)Database::fetch("SELECT MAX(id) as id FROM branding")['id'];
            Database::update('branding', $brandId, ['logo_dark' => '/uploads/' . $filename]);
        }

        flash('success', __('settings_saved'));
        return Response::redirect('/admin/settings/branding');
    }
}
