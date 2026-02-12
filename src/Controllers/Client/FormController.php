<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use App\Core\{Request, Response, Database};
use App\Models\Client;

class FormController
{
    /**
     * Show form wizard for client.
     */
    public function show(Request $request): Response
    {
        $token = $request->param('token');
        $client = Client::findByFormToken($token);

        if (!$client) {
            return Response::error(404, __('form_expired'));
        }

        // Get assigned form template (default if none specific)
        $template = Database::fetch(
            "SELECT ft.* FROM form_template_clients ftc 
             JOIN form_templates ft ON ft.id = ftc.form_template_id 
             WHERE ftc.client_id = ? AND ft.is_active = 1 LIMIT 1",
            [$client['id']]
        ) ?: Database::fetch("SELECT * FROM form_templates WHERE is_default = 1 AND is_active = 1 LIMIT 1");

        if (!$template) {
            return Response::error(404, __('form_expired'));
        }

        // Get existing response (for resume/autosave)
        $existingResponse = Database::fetch(
            "SELECT * FROM form_responses WHERE client_id = ? AND form_template_id = ? ORDER BY id DESC LIMIT 1",
            [$client['id'], $template['id']]
        );

        if ($existingResponse && $existingResponse['is_submitted']) {
            return Response::view('pages/client/form-thank-you', [
                'pageTitle' => __('form_thank_you'),
                'client'    => $client,
            ], 200, 'client');
        }

        // Load branding
        $branding = Database::fetch("SELECT * FROM branding LIMIT 1");

        return Response::view('pages/client/form-fill', [
            'pageTitle'   => __('form_title'),
            'pageScript'  => 'form-wizard.js',
            'client'      => $client,
            'template'    => $template,
            'structure'   => json_decode($template['structure'], true),
            'responses'   => $existingResponse ? json_decode($existingResponse['responses'], true) : [],
            'responseId'  => $existingResponse['id'] ?? null,
            'brandName'   => $branding['company_name'] ?? 'LLMInvoice',
            'brandLogo'   => $branding['logo_dark'] ?? null,
            'brandColor'  => $branding['primary_color'] ?? '#C8FF00',
        ], 200, 'client');
    }

    /**
     * Autosave form responses (AJAX).
     */
    public function autosave(Request $request): Response
    {
        $token = $request->param('token');
        $client = Client::findByFormToken($token);

        if (!$client) {
            return Response::json(['error' => 'Invalid token'], 404);
        }

        $responses = $request->input('responses', []);
        $completionPct = $request->input('completion_pct', 0);

        // Upsert form response
        $existing = Database::fetch(
            "SELECT id FROM form_responses WHERE client_id = ? AND is_submitted = 0 ORDER BY id DESC LIMIT 1",
            [$client['id']]
        );

        if ($existing) {
            Database::update('form_responses', (int)$existing['id'], [
                'responses'      => json_encode($responses),
                'completion_pct' => $completionPct,
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
            $responseId = $existing['id'];
        } else {
            $templateId = $request->input('template_id');
            $responseId = Database::insert('form_responses', [
                'client_id'        => $client['id'],
                'form_template_id' => $templateId,
                'responses'        => json_encode($responses),
                'completion_pct'   => $completionPct,
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
        }

        return Response::json(['success' => true, 'response_id' => $responseId]);
    }

    /**
     * Submit form (finalize).
     */
    public function submit(Request $request): Response
    {
        $token = $request->param('token');
        $client = Client::findByFormToken($token);

        if (!$client) {
            return Response::json(['error' => 'Invalid token'], 404);
        }

        $responses = $request->input('responses', []);

        // Find or create response
        $existing = Database::fetch(
            "SELECT id FROM form_responses WHERE client_id = ? AND is_submitted = 0 ORDER BY id DESC LIMIT 1",
            [$client['id']]
        );

        if ($existing) {
            Database::update('form_responses', (int)$existing['id'], [
                'responses'      => json_encode($responses),
                'completion_pct' => 100,
                'is_submitted'   => 1,
                'submitted_at'   => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
        } else {
            Database::insert('form_responses', [
                'client_id'        => $client['id'],
                'form_template_id' => $request->input('template_id'),
                'responses'        => json_encode($responses),
                'completion_pct'   => 100,
                'is_submitted'     => 1,
                'submitted_at'     => date('Y-m-d H:i:s'),
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
        }

        return Response::json(['success' => true, 'redirect' => "/form/{$token}/thank-you"]);
    }
}
