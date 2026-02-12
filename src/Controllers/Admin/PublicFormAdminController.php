<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Database};

class PublicFormAdminController
{
    /**
     * List all public form submissions (pending review).
     */
    public function index(Request $request): Response
    {
        $page = (int) ($request->query('page') ?? 1);
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $total = Database::count('clients', "source = 'public_form'");
        $submissions = Database::fetchAll(
            "SELECT c.*, pc.name_" . \App\Core\App::locale() . " as column_name, pc.color as column_color
             FROM clients c
             LEFT JOIN pipeline_columns pc ON c.pipeline_column_id = pc.id
             WHERE c.source = 'public_form'
             ORDER BY c.created_at DESC
             LIMIT {$perPage} OFFSET {$offset}"
        );

        $pagination = \App\Core\Pagination::calculate($total, $perPage, $page);

        return Response::view('pages/admin/public-forms', [
            'pageTitle'   => __('nav_public_forms'),
            'submissions' => $submissions,
            'pagination'  => $pagination,
        ], 200, 'admin');
    }
}
