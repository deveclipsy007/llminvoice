<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Session, Database};

class FormBuilderController
{
    /**
     * List form templates.
     */
    public function index(Request $request): Response
    {
        $templates = Database::fetchAll(
            "SELECT ft.*, u.name as creator_name FROM form_templates ft LEFT JOIN users u ON ft.created_by = u.id ORDER BY ft.created_at DESC"
        );

        return Response::view('pages/admin/form-builder', [
            'pageTitle'  => __('nav_forms'),
            'templates'  => $templates,
        ], 200, 'admin');
    }

    /**
     * Show form to create new template.
     */
    public function create(Request $request): Response
    {
        return Response::view('pages/admin/form-editor', [
            'pageTitle' => __('client_forms_new'),
            'template'  => null,
        ], 200, 'admin');
    }

    /**
     * Show form to edit existing template.
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->param('id');
        $template = Database::fetch("SELECT * FROM form_templates WHERE id = ?", [$id]);

        if (!$template) {
            Session::flash('error', 'Template not found');
            return Response::redirect('/admin/settings/forms');
        }

        return Response::view('pages/admin/form-editor', [
            'pageTitle' => __('edit') . ': ' . $template['name'],
            'template'  => $template,
        ], 200, 'admin');
    }

    /**
     * Save template (create or update).
     */
    public function save(Request $request): Response
    {
        $id = $request->input('id');
        $data = [
            'name'        => $request->input('name', 'Untitled'),
            'description' => $request->input('description', ''),
            'structure'   => $request->input('structure', '{"sections":[]}'),
            'is_active'   => $request->input('is_active', 1),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        if ($id) {
            Database::update('form_templates', $data, 'id = ?', [(int)$id]);
        } else {
            $data['created_by'] = Session::userId();
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = Database::insert('form_templates', $data);
        }

        return Response::json(['success' => true, 'id' => $id]);
    }

    /**
     * Get template structure as JSON.
     */
    public function get(Request $request): Response
    {
        $id = (int) $request->param('id');
        $template = Database::fetch("SELECT * FROM form_templates WHERE id = ?", [$id]);

        if (!$template) {
            return Response::json(['error' => 'Template not found'], 404);
        }

        return Response::json(['template' => $template]);
    }

    /**
     * Delete template.
     */
    public function delete(Request $request): Response
    {
        $id = (int) $request->param('id');
        Database::delete('form_templates', 'id = ?', [(int)$id]);
        return Response::json(['success' => true]);
    }

    /**
     * Translate form structure using AI.
     */
    public function translate(Request $request): Response
    {
        $structure = $request->input('structure');
        
        if (is_string($structure)) {
            $structure = json_decode($structure, true);
        }

        if (!is_array($structure)) {
            return Response::error(400, 'Invalid structure format');
        }

        try {
            $aiService = new \App\Services\AiTranslationService();
            $translatedStructure = $aiService->translateForm($structure);
            
            return Response::json([
                'success' => true,
                'structure' => $translatedStructure
            ]);
        } catch (\Exception $e) {
            return Response::error(500, 'Translation failed: ' . $e->getMessage());
        }
    }
}
