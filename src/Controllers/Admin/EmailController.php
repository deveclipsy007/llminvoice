<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Database};

class EmailController
{
    /**
     * View email logs.
     */
    public function index(Request $request): Response
    {
        $logs = Database::fetchAll(
            "SELECT el.*, p.id as proposal_id 
             FROM email_logs el 
             LEFT JOIN proposals p ON el.proposal_id = p.id
             ORDER BY el.created_at DESC
             LIMIT 50"
        );

        return Response::view('pages/admin/email-logs', [
            'pageTitle' => __('nav_email'),
            'logs'      => $logs,
        ], 200, 'admin');
    }
}
