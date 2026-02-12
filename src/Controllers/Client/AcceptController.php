<?php

declare(strict_types=1);

namespace App\Controllers\Client;

use App\Core\{Request, Response};
use App\Services\ProposalService;

class AcceptController
{
    /**
     * Handle client accept/reject of proposal.
     */
    public function respond(Request $request): Response
    {
        $token  = $request->param('token');
        $action = $request->input('action'); // 'accept' or 'reject'
        $feedback = $request->input('feedback');

        if (!in_array($action, ['accept', 'reject'])) {
            return Response::json(['error' => 'Invalid action'], 400);
        }

        $success = ProposalService::handleClientResponse($token, $action, $feedback);

        if (!$success) {
            return Response::json(['error' => 'Proposal not found'], 404);
        }

        return Response::json([
            'success' => true,
            'message' => $action === 'accept' ? __('proposal_accepted') : __('proposal_rejected'),
        ]);
    }
}
