<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Session, Database};
use App\Services\PipelineService;

class KanbanController
{
    /**
     * Render the Kanban board page.
     */
    public function index(Request $request): Response
    {
        $columns = PipelineService::getColumnsWithCounts();

        // Fetch clients for each column
        $search = $request->query('search');
        $temperature = $request->query('temperature');

        foreach ($columns as &$col) {
            $col['clients'] = PipelineService::getClientsByColumn(
                (int) $col['id'],
                $search,
                $temperature
            );
        }
        unset($col);

        return Response::view('pages/admin/kanban', [
            'pageTitle'   => __('kanban_title'),
            'pageScript'  => 'kanban.js',
            'columns'     => $columns,
            'search'      => $search,
            'temperature' => $temperature,
        ], 200, 'admin');
    }

    /**
     * API: Get columns data (JSON).
     */
    public function columns(Request $request): Response
    {
        $columns = PipelineService::getColumnsWithCounts();
        $search = $request->query('search');
        $temperature = $request->query('temperature');

        foreach ($columns as &$col) {
            $col['clients'] = PipelineService::getClientsByColumn(
                (int) $col['id'],
                $search,
                $temperature
            );
        }
        unset($col);

        return Response::json(['columns' => $columns]);
    }

    /**
     * API: Move a client to a new column (AJAX).
     */
    public function move(Request $request): Response
    {
        $clientId   = (int) $request->input('client_id');
        $toColumnId = (int) $request->input('to_column_id');
        $position   = (int) $request->input('position', 0);

        if (!$clientId || !$toColumnId) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        // Get client's current column
        $client = Database::fetch("SELECT pipeline_column_id FROM clients WHERE id = ?", [$clientId]);
        if (!$client) {
            return Response::json(['error' => 'Client not found'], 404);
        }

        $fromColumnId = (int) $client['pipeline_column_id'];

        // If moving to a different column, validate transition
        if ($fromColumnId !== $toColumnId) {
            $validation = PipelineService::validateTransition($clientId, $fromColumnId, $toColumnId);
            if (!$validation['allowed']) {
                return Response::json([
                    'error'  => __('kanban_move_blocked'),
                    'errors' => $validation['errors'],
                ], 422);
            }
        }

        PipelineService::moveClient($clientId, $toColumnId, $position);

        // Log the move
        \App\Core\Logger::info("Client #{$clientId} moved from column #{$fromColumnId} to #{$toColumnId}", [
            'user_id' => Session::userId(),
        ]);

        return Response::json(['success' => true, 'message' => 'Client moved successfully']);
    }

    /**
     * API: Reorder clients within a column (AJAX).
     */
    public function reorder(Request $request): Response
    {
        $columnId = (int) $request->input('column_id');
        $order    = $request->input('order', []);

        if (!$columnId || empty($order)) {
            return Response::json(['error' => 'Missing required fields'], 400);
        }

        foreach ($order as $position => $clientId) {
            Database::update('clients', (int) $clientId, [
                'position_in_column' => $position,
                'updated_at'         => date('Y-m-d H:i:s'),
            ]);
        }

        return Response::json(['success' => true]);
    }
}
