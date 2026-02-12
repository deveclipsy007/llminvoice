<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Session, Database, Validator, Pagination};
use App\Models\Client;

class ClientController
{
    /**
     * List all clients with pagination.
     */
    public function index(Request $request): Response
    {
        $page     = (int) ($request->query('page') ?? 1);
        $perPage  = 15;
        $search   = $request->query('search');
        $temperature = $request->query('temperature');

        $where = 'is_archived = 0';
        $params = [];

        if ($search) {
            $where .= " AND (contact_name LIKE ? OR company_name LIKE ? OR contact_email LIKE ?)";
            $term = "%{$search}%";
            $params = [$term, $term, $term];
        }

        if ($temperature) {
            $where .= " AND temperature = ?";
            $params[] = $temperature;
        }

        $total  = Database::count('clients', $where, $params);
        $offset = ($page - 1) * $perPage;

        $clients = Database::fetchAll(
            "SELECT c.*, pc.name_" . \App\Core\App::locale() . " as column_name, pc.color as column_color, u.name as assigned_name
             FROM clients c
             LEFT JOIN pipeline_columns pc ON c.pipeline_column_id = pc.id
             LEFT JOIN users u ON c.assigned_user_id = u.id
             WHERE c.{$where}
             ORDER BY c.updated_at DESC, c.id DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $pagination = Pagination::calculate($total, $perPage, $page);

        return Response::view('pages/admin/clients-list', [
            'pageTitle'   => __('clients_title'),
            'clients'     => $clients,
            'pagination'  => $pagination,
            'search'      => $search,
            'temperature' => $temperature,
        ], 200, 'admin');
    }

    /**
     * Show create form.
     */
    public function create(Request $request): Response
    {
        $columns = Database::fetchAll("SELECT * FROM pipeline_columns ORDER BY sort_order");
        $users   = Database::fetchAll("SELECT id, name FROM users WHERE is_active = 1 ORDER BY name");

        return Response::view('pages/admin/client-form', [
            'pageTitle' => __('clients_new'),
            'client'    => null,
            'columns'   => $columns,
            'users'     => $users,
            'errors'    => [],
        ], 200, 'admin');
    }

    /**
     * Store a new client.
     */
    public function store(Request $request): Response
    {
        $data = $request->only(['contact_name', 'company_name', 'contact_email', 'contact_phone', 'website', 'temperature', 'pipeline_column_id', 'assigned_user_id']);

        $validator = Validator::make($data)
            ->required('contact_name', __('contact_name'))
            ->minLength('contact_name', 2, __('contact_name'))
            ->email('contact_email', __('contact_email'));

        // Validate temperature if present
        if (isset($data['temperature']) && !in_array($data['temperature'], ['cold', 'warm', 'hot'])) {
            // Temperature will be validated in the fails check
        }

        if ($validator->fails()) {
            flash('error', __('error'));
            return Response::redirect('/admin/clients/create');
        }

        $data['uuid'] = generate_uuid();
        $data['form_token'] = generate_token();
        $data['source'] = 'manual';
        $data['created_at'] = date('Y-m-d H:i:s');

        $id = Client::create($data);

        // Log
        \App\Core\Logger::info("Client created: #{$id}", ['user_id' => Session::userId()]);

        flash('success', __('client_created_msg'));
        return Response::redirect("/admin/clients/{$id}");
    }

    /**
     * Show client detail page.
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->param('id');
        $client = Client::find($id);

        if (!$client) {
            return Response::error(404, __('page_not_found'));
        }

        // Fetch related data
        $notes = Database::fetchAll(
            "SELECT n.*, u.name as user_name FROM notes n LEFT JOIN users u ON n.user_id = u.id WHERE n.client_id = ? ORDER BY n.created_at DESC",
            [$id]
        );

        $formResponses = Database::fetchAll(
            "SELECT fr.*, ft.name as template_name FROM form_responses fr LEFT JOIN form_templates ft ON fr.form_template_id = ft.id WHERE fr.client_id = ? ORDER BY fr.created_at DESC",
            [$id]
        );

        $aiAnalyses = Database::fetchAll(
            "SELECT * FROM ai_analyses WHERE client_id = ? ORDER BY created_at DESC",
            [$id]
        );

        $proposals = Database::fetchAll(
            "SELECT p.*, pv.total_value, pv.version_number FROM proposals p LEFT JOIN proposal_versions pv ON pv.id = p.current_version_id WHERE p.client_id = ? ORDER BY p.created_at DESC",
            [$id]
        );

        $column = Database::fetch("SELECT * FROM pipeline_columns WHERE id = ?", [$client['pipeline_column_id']]);

        return Response::view('pages/admin/client-detail', [
            'pageTitle'     => e($client['contact_name']),
            'client'        => $client,
            'column'        => $column,
            'notes'         => $notes,
            'formResponses' => $formResponses,
            'aiAnalyses'    => $aiAnalyses,
            'proposals'     => $proposals,
        ], 200, 'admin');
    }

    /**
     * Show edit form.
     */
    public function edit(Request $request): Response
    {
        $id = (int) $request->param('id');
        $client = Client::find($id);

        if (!$client) {
            return Response::error(404, __('page_not_found'));
        }

        $columns = Database::fetchAll("SELECT * FROM pipeline_columns ORDER BY sort_order");
        $users   = Database::fetchAll("SELECT id, name FROM users WHERE is_active = 1 ORDER BY name");

        return Response::view('pages/admin/client-form', [
            'pageTitle' => __('edit') . ': ' . e($client['contact_name']),
            'client'    => $client,
            'columns'   => $columns,
            'users'     => $users,
            'errors'    => [],
        ], 200, 'admin');
    }

    /**
     * Update a client.
     */
    public function update(Request $request): Response
    {
        $id = (int) $request->param('id');
        $client = Client::find($id);

        if (!$client) {
            return Response::error(404, __('page_not_found'));
        }

        $data = $request->only(['contact_name', 'company_name', 'contact_email', 'contact_phone', 'website', 'temperature', 'pipeline_column_id', 'assigned_user_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');

        Client::update($id, $data);

        flash('success', __('client_updated_msg'));
        return Response::redirect("/admin/clients/{$id}");
    }

    /**
     * API: Add note to client.
     */
    public function addNote(Request $request): Response
    {
        $id = (int) $request->param('id');
        $content = $request->input('content');
        $type = $request->input('type', 'note');

        if (!$content) {
            return Response::json(['error' => 'Content required'], 400);
        }

        $noteId = Database::insert('notes', [
            'client_id'  => $id,
            'user_id'    => Session::userId(),
            'type'       => $type,
            'content'    => $content,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        // Increment notes count
        Database::execute("UPDATE clients SET notes_count = notes_count + 1 WHERE id = ?", [$id]);

        return Response::json(['success' => true, 'note_id' => $noteId]);
    }

    /**
     * API: Archive client.
     */
    public function archive(Request $request): Response
    {
        $id = (int) $request->param('id');
        Client::archive($id);
        flash('success', __('client_archived'));
        return Response::json(['success' => true]);
    }
}
