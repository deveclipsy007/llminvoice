<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use App\Core\{Request, Response, Database};
use App\Services\ProposalService;

class ProposalViewController
{
    /**
     * Show proposal via share token.
     */
    public function show(Request $request): Response
    {
        $token = $request->param('token');
        $proposal = Database::fetch("SELECT * FROM proposals WHERE share_token = ?", [$token]);

        if (!$proposal) {
            return Response::error(404, __('page_not_found'));
        }

        $proposal = ProposalService::getProposalWithVersions((int) $proposal['id']);
        $branding = Database::fetch("SELECT * FROM branding LIMIT 1");
        $version = $proposal['current_version'];
        $content = json_decode($version['content'] ?? '{}', true);

        // Track view
        Database::insert('proposal_views', [
            'proposal_id' => $proposal['id'],
            'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'viewed_at'   => date('Y-m-d H:i:s'),
        ]);

        return Response::view('pages/client/proposal-view', [
            'pageTitle'  => __('proposal_view'),
            'proposal'   => $proposal,
            'version'    => $version,
            'content'    => $content,
            'token'      => $token,
            'brandName'  => $branding['company_name'] ?? 'LLMInvoice',
            'brandLogo'  => $branding['logo_dark'] ?? null,
            'brandColor' => $branding['primary_color'] ?? '#C8FF00',
        ], 200, 'client');
    }
}
