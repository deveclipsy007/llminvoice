<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Session, Database};
use App\Services\{ProposalService, PdfService, EmailService};

class ProposalController
{
    /**
     * View/edit proposal.
     */
    public function show(Request $request): Response
    {
        $id = (int) $request->param('id');
        $proposal = ProposalService::getProposalWithVersions($id);

        if (!$proposal) return Response::error(404, __('page_not_found'));

        return Response::view('pages/admin/proposal-editor', [
            'pageTitle' => __('proposal_edit') . ' #' . $id,
            'pageScript' => 'proposal-editor.js',
            'proposal' => $proposal,
        ], 200, 'admin');
    }

    /**
     * Create proposal from AI analysis.
     */
    public function createFromAnalysis(Request $request): Response
    {
        $clientId   = (int) $request->input('client_id');
        $analysisId = (int) $request->input('analysis_id');

        try {
            $proposalId = ProposalService::createFromAnalysis($clientId, $analysisId, (int) Session::userId());
            return Response::json(['success' => true, 'proposal_id' => $proposalId]);
        } catch (\Throwable $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Save new version.
     */
    public function saveVersion(Request $request): Response
    {
        $proposalId = (int) $request->param('id');
        $data = [
            'content' => json_decode($request->input('content', '{}'), true),
            'total_value' => (float) $request->input('total_value', 0),
            'discount_pct' => (float) $request->input('discount_pct', 0),
            'validity_days' => (int) $request->input('validity_days', 30),
            'payment_conditions' => $request->input('payment_conditions', ''),
        ];

        $versionId = ProposalService::createNewVersion($proposalId, $data);
        return Response::json(['success' => true, 'version_id' => $versionId]);
    }

    /**
     * Generate PDF and download.
     */
    public function downloadPdf(Request $request): Response
    {
        $id = (int) $request->param('id');

        try {
            $pdf = PdfService::generateProposal($id);
            return Response::download($pdf, "proposta_{$id}.pdf", 'application/pdf');
        } catch (\Throwable $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send proposal to client via email.
     */
    public function send(Request $request): Response
    {
        $id = (int) $request->param('id');
        $token = ProposalService::generateShareToken($id);
        $shareUrl = url("/proposal/{$token}");

        $success = EmailService::sendProposalLink($id, $shareUrl);

        if ($success) {
            flash('success', __('proposal_email_sent'));
            return Response::json(['success' => true, 'share_url' => $shareUrl]);
        }

        return Response::json(['error' => __('proposal_email_error')], 500);
    }
}
