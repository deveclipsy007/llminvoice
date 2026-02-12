<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\{Request, Response, Session, Database};
use App\Services\AiService;

class AiController
{
    /**
     * Trigger AI analysis for a client.
     */
    public function analyze(Request $request): Response
    {
        $clientId = (int) $request->param('id');
        $client = Database::fetch("SELECT * FROM clients WHERE id = ?", [$clientId]);

        if (!$client) {
            return Response::json(['error' => 'Client not found'], 404);
        }

        try {
            $analysisId = AiService::analyzeClient($clientId, (int) Session::userId());

            return Response::json([
                'success'     => true,
                'analysis_id' => $analysisId,
                'message'     => __('ai_completed'),
            ]);
        } catch (\Throwable $e) {
            \App\Core\Logger::error("AI analysis failed for client #{$clientId}: " . $e->getMessage());
            return Response::json([
                'error'   => __('ai_failed'),
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get analysis status.
     */
    public function status(Request $request): Response
    {
        $analysisId = (int) $request->param('id');
        $analysis = Database::fetch("SELECT * FROM ai_analyses WHERE id = ?", [$analysisId]);

        if (!$analysis) {
            return Response::json(['error' => 'Analysis not found'], 404);
        }

        return Response::json([
            'status'  => $analysis['status'],
            'message' => match($analysis['status']) {
                'pending'    => __('ai_analyzing'),
                'processing' => __('ai_analyzing'),
                'completed'  => __('ai_completed'),
                'failed'     => $analysis['error_message'] ?? __('ai_failed'),
                default      => '',
            },
        ]);
    }

    /**
     * Get analysis result.
     */
    public function result(Request $request): Response
    {
        $analysisId = (int) $request->param('id');
        $analysis = Database::fetch("SELECT * FROM ai_analyses WHERE id = ?", [$analysisId]);

        if (!$analysis) {
            return Response::json(['error' => 'Analysis not found'], 404);
        }

        return Response::json([
            'analysis' => [
                'id'                 => $analysis['id'],
                'status'             => $analysis['status'],
                'provider'           => $analysis['provider'],
                'model'              => $analysis['model'],
                'diagnosis'          => $analysis['diagnosis'],
                'recommendations'    => json_decode($analysis['recommendations'] ?? '[]', true),
                'risks'              => json_decode($analysis['risks'] ?? '[]', true),
                'proposal_structure' => json_decode($analysis['proposal_structure'] ?? '{}', true),
                'pricing_range'      => json_decode($analysis['pricing_range'] ?? '{}', true),
                'execution_plan'     => $analysis['execution_plan'],
                'tokens_input'       => $analysis['tokens_input'],
                'tokens_output'      => $analysis['tokens_output'],
                'cost_usd'           => $analysis['cost_usd'],
                'processing_time_ms' => $analysis['processing_time_ms'],
                'created_at'         => $analysis['created_at'],
            ],
        ]);
    }
}
