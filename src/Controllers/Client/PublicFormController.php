<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use App\Core\{Request, Response, Database};

class PublicFormController
{
    /**
     * Show public diagnostic form.
     */
    public function show(Request $request): Response
    {
        $branding = Database::fetch("SELECT * FROM branding LIMIT 1");
        $template = Database::fetch("SELECT * FROM form_templates WHERE is_default = 1 AND is_active = 1 LIMIT 1");

        if (!$template) {
            return Response::error(404, __('form_expired'));
        }

        $structure = json_decode($template['structure'], true);

        return Response::view('pages/public/diagnostico', [
            'pageTitle'       => __('diag_title'),
            'pageDescription' => __('diag_subtitle'),
            'pageScript'      => 'diagnostico.js',
            'template'        => $template,
            'structure'        => $structure,
            'brandName'       => $branding['company_name'] ?? 'LLMInvoice',
        ], 200, 'landing');
    }

    /**
     * Submit public diagnostic form.
     */
    public function submit(Request $request): Response
    {
        $data = $request->only(['name', 'email', 'phone', 'company', 'website']);
        $responses = $request->input('responses', []);
        $templateId = (int) $request->input('template_id');

        // Create client from form submission
        $clientId = Database::insert('clients', [
            'uuid'               => generate_uuid(),
            'form_token'         => generate_token(),
            'contact_name'       => $data['name'] ?? '',
            'contact_email'      => $data['email'] ?? '',
            'contact_phone'      => $data['phone'] ?? '',
            'company_name'       => $data['company'] ?? '',
            'website'            => $data['website'] ?? '',
            'source'             => 'public_form',
            'temperature'        => 'warm',
            'pipeline_column_id' => 1, // First column (Lead)
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        // Save form response
        Database::insert('form_responses', [
            'client_id'        => $clientId,
            'form_template_id' => $templateId,
            'responses'        => json_encode($responses),
            'completion_pct'   => 100,
            'is_submitted'     => 1,
            'submitted_at'     => date('Y-m-d H:i:s'),
            'created_at'       => date('Y-m-d H:i:s'),
        ]);

        // Return success
        if ($request->isAjax()) {
            return Response::json(['success' => true, 'redirect' => '/diagnostico/obrigado']);
        }

        return Response::redirect('/diagnostico/obrigado');
    }

    /**
     * Thank you page.
     */
    public function thankYou(Request $request): Response
    {
        return Response::view('pages/public/diagnostico-obrigado', [
            'pageTitle' => __('diag_thank_you_title'),
        ], 200, 'landing');
    }
}
