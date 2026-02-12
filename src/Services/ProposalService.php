<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

/**
 * ProposalService - Create and manage proposals with versioning.
 */
class ProposalService
{
    /**
     * Create a proposal from AI analysis or manual input.
     */
    public static function createFromAnalysis(int $clientId, int $analysisId, int $createdBy): int
    {
        $analysis = Database::fetch("SELECT * FROM ai_analyses WHERE id = ?", [$analysisId]);
        if (!$analysis) throw new \RuntimeException("Analysis not found: {$analysisId}");

        $structure = json_decode($analysis['proposal_structure'] ?? '{}', true);
        $pricing = json_decode($analysis['pricing_range'] ?? '{}', true);

        // Create proposal
        $proposalId = Database::insert('proposals', [
            'client_id'     => $clientId,
            'ai_analysis_id' => $analysisId,
            'status'        => 'draft',
            'created_by'    => $createdBy,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        // Create first version
        $versionId = Database::insert('proposal_versions', [
            'proposal_id'       => $proposalId,
            'version_number'    => 1,
            'content'           => json_encode($structure),
            'total_value'       => $pricing['max'] ?? 0,
            'discount_pct'      => 0,
            'validity_days'     => 30,
            'payment_conditions'=> 'A combinar',
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        // Set current version
        Database::update('proposals', $proposalId, [
            'current_version_id' => $versionId,
        ]);

        return $proposalId;
    }

    /**
     * Create a new version of a proposal.
     */
    public static function createNewVersion(int $proposalId, array $data): int
    {
        $currentVersion = Database::fetch(
            "SELECT MAX(version_number) as max_ver FROM proposal_versions WHERE proposal_id = ?",
            [$proposalId]
        );
        $nextVersion = ($currentVersion['max_ver'] ?? 0) + 1;

        $versionId = Database::insert('proposal_versions', [
            'proposal_id'        => $proposalId,
            'version_number'     => $nextVersion,
            'content'            => json_encode($data['content'] ?? []),
            'total_value'        => $data['total_value'] ?? 0,
            'discount_pct'       => $data['discount_pct'] ?? 0,
            'validity_days'      => $data['validity_days'] ?? 30,
            'payment_conditions' => $data['payment_conditions'] ?? '',
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        Database::update('proposals', $proposalId, [
            'current_version_id' => $versionId,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return $versionId;
    }

    /**
     * Get full proposal data with versions.
     */
    public static function getProposalWithVersions(int $proposalId): ?array
    {
        $proposal = Database::fetch(
            "SELECT p.*, c.contact_name, c.company_name, c.contact_email
             FROM proposals p
             JOIN clients c ON c.id = p.client_id
             WHERE p.id = ?",
            [$proposalId]
        );

        if (!$proposal) return null;

        $proposal['versions'] = Database::fetchAll(
            "SELECT * FROM proposal_versions WHERE proposal_id = ? ORDER BY version_number DESC",
            [$proposalId]
        );

        $proposal['current_version'] = Database::fetch(
            "SELECT * FROM proposal_versions WHERE id = ?",
            [$proposal['current_version_id']]
        );

        return $proposal;
    }

    /**
     * Generate shared link token for client view.
     */
    public static function generateShareToken(int $proposalId): string
    {
        $token = bin2hex(random_bytes(32));
        Database::update('proposals', $proposalId, [
            'share_token' => $token,
            'status' => 'sent',
            'sent_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        return $token;
    }

    /**
     * Handle client acceptance or rejection.
     */
    public static function handleClientResponse(string $shareToken, string $action, ?string $feedback = null): bool
    {
        $proposal = Database::fetch("SELECT * FROM proposals WHERE share_token = ?", [$shareToken]);
        if (!$proposal) return false;

        $status = $action === 'accept' ? 'accepted' : 'rejected';
        Database::update('proposals', (int)$proposal['id'], [
            'status' => $status,
            'client_feedback' => $feedback,
            'client_responded_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return true;
    }
}
