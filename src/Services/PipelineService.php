<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

/**
 * PipelineService - validates pipeline transitions and manages column data.
 */
class PipelineService
{
    /**
     * Get all pipeline columns with client counts.
     */
    public static function getColumnsWithCounts(): array
    {
        return Database::fetchAll(
            "SELECT pc.*, 
                    (SELECT COUNT(*) FROM clients c WHERE c.pipeline_column_id = pc.id AND c.is_archived = 0) as client_count
             FROM pipeline_columns pc 
             ORDER BY pc.sort_order ASC"
        );
    }

    /**
     * Get clients for a specific pipeline column.
     */
    public static function getClientsByColumn(int $columnId, ?string $search = null, ?string $temperature = null): array
    {
        $sql = "SELECT c.*, u.name as assigned_name
                FROM clients c
                LEFT JOIN users u ON c.assigned_user_id = u.id
                WHERE c.pipeline_column_id = ? AND c.is_archived = 0";
        $params = [$columnId];

        if ($search) {
            $sql .= " AND (c.contact_name LIKE ? OR c.company_name LIKE ? OR c.contact_email LIKE ?)";
            $searchTerm = "%{$search}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        if ($temperature) {
            $sql .= " AND c.temperature = ?";
            $params[] = $temperature;
        }

        $sql .= " ORDER BY c.position_in_column ASC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Validate if a client can move from one column to another.
     * Returns ['allowed' => bool, 'errors' => string[]]
     */
    public static function validateTransition(int $clientId, int $fromColumnId, int $toColumnId): array
    {
        $locale = \App\Core\App::locale();
        $errorField = "error_message_{$locale}";

        $rules = Database::fetchAll(
            "SELECT * FROM pipeline_transition_rules 
             WHERE (from_column_id = ? OR from_column_id IS NULL) AND to_column_id = ?",
            [$fromColumnId, $toColumnId]
        );

        $errors = [];

        foreach ($rules as $rule) {
            switch ($rule['rule_type']) {
                case 'min_responses':
                    $response = Database::fetch(
                        "SELECT completion_pct FROM form_responses WHERE client_id = ? ORDER BY id DESC LIMIT 1",
                        [$clientId]
                    );
                    $pct = $response ? (float) $response['completion_pct'] : 0;
                    if ($pct < (float) $rule['rule_value']) {
                        $errors[] = $rule[$errorField] ?? $rule['error_message_en'] ?? 'Transition rule not met.';
                    }
                    break;

                case 'ai_completed':
                    $analysis = Database::fetch(
                        "SELECT id FROM ai_analyses WHERE client_id = ? AND status = 'completed' LIMIT 1",
                        [$clientId]
                    );
                    if (!$analysis) {
                        $errors[] = $rule[$errorField] ?? $rule['error_message_en'] ?? 'AI analysis required.';
                    }
                    break;

                case 'proposal_approved':
                    $proposal = Database::fetch(
                        "SELECT id FROM proposals WHERE client_id = ? AND status IN ('approved', 'sent', 'accepted') LIMIT 1",
                        [$clientId]
                    );
                    if (!$proposal) {
                        $errors[] = $rule[$errorField] ?? $rule['error_message_en'] ?? 'Approved proposal required.';
                    }
                    break;

                case 'acceptance_confirmed':
                    $proposal = Database::fetch(
                        "SELECT id FROM proposals WHERE client_id = ? AND status IN ('accepted', 'rejected') LIMIT 1",
                        [$clientId]
                    );
                    if (!$proposal) {
                        $errors[] = $rule[$errorField] ?? $rule['error_message_en'] ?? 'Client response required.';
                    }
                    break;

                case 'archive_reason':
                    // This is checked at move time — user must provide a reason string
                    break;
            }
        }

        return [
            'allowed' => empty($errors),
            'errors'  => $errors,
        ];
    }

    /**
     * Move client to a new column and position.
     */
    public static function moveClient(int $clientId, int $toColumnId, int $position = 0): bool
    {
        // Shift existing cards down at the target position
        Database::execute(
            "UPDATE clients 
             SET position_in_column = position_in_column + 1 
             WHERE pipeline_column_id = ? AND position_in_column >= ? AND is_archived = 0",
            [$toColumnId, $position]
        );

        // Move the client
        Database::update('clients', $clientId, [
            'pipeline_column_id' => $toColumnId,
            'position_in_column' => $position,
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);

        return true;
    }

    /**
     * Get dashboard metrics.
     */
    public static function getDashboardMetrics(): array
    {
        $totalClients = Database::count('clients', 'is_archived = 0');
        $leadsToday = Database::count('clients', "is_archived = 0 AND DATE(created_at) = DATE('now')");
        $proposalsSent = Database::count('proposals', "status IN ('sent', 'accepted', 'rejected')");
        $pendingAnalyses = Database::count('ai_analyses', "status = 'pending'");

        // Revenue from accepted proposals
        $revenueRow = Database::fetch(
            "SELECT COALESCE(SUM(pv.total_value), 0) as total 
             FROM proposals p
             JOIN proposal_versions pv ON pv.id = p.current_version_id
             WHERE p.status = 'accepted'"
        );
        $totalRevenue = $revenueRow ? (float) $revenueRow['total'] : 0;

        // Conversion rate (accepted / total closed)
        $closed = Database::count('proposals', "status IN ('accepted', 'rejected')");
        $accepted = Database::count('proposals', "status = 'accepted'");
        $conversionRate = $closed > 0 ? round(($accepted / $closed) * 100, 1) : 0;

        return [
            'total_clients'    => $totalClients,
            'leads_today'      => $leadsToday,
            'proposals_sent'   => $proposalsSent,
            'pending_analyses' => $pendingAnalyses,
            'total_revenue'    => $totalRevenue,
            'conversion_rate'  => $conversionRate,
        ];
    }

    /**
     * Get recent activity (latest notes + pipeline moves).
     */
    public static function getRecentActivity(int $limit = 10): array
    {
        return Database::fetchAll(
            "SELECT n.*, c.contact_name, c.company_name, u.name as user_name
             FROM notes n
             LEFT JOIN clients c ON n.client_id = c.id
             LEFT JOIN users u ON n.user_id = u.id
             ORDER BY n.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
}
