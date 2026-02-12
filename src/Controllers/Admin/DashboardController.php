<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Session};
use App\Services\PipelineService;

class DashboardController
{
    public function index(Request $request): Response
    {
        $metrics  = PipelineService::getDashboardMetrics();
        $columns  = PipelineService::getColumnsWithCounts();
        $activity = PipelineService::getRecentActivity(8);

        return Response::view('pages/admin/dashboard', [
            'pageTitle' => __('dashboard_title'),
            'metrics'   => $metrics,
            'columns'   => $columns,
            'activity'  => $activity,
        ], 200, 'admin');
    }
}
