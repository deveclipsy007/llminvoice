<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

/**
 * AiPromptBuilder - Constructs prompts for AI analysis and email generation.
 */
class AiPromptBuilder
{
    /**
     * Build a comprehensive client analysis prompt.
     */
    public static function buildClientAnalysisPrompt(int $clientId): string
    {
        $client = Database::fetch("SELECT * FROM clients WHERE id = ?", [$clientId]);
        if (!$client) throw new \RuntimeException("Client not found: {$clientId}");

        // Get form responses
        $formResponse = Database::fetch(
            "SELECT fr.responses, ft.structure FROM form_responses fr
             LEFT JOIN form_templates ft ON fr.form_template_id = ft.id
             WHERE fr.client_id = ? AND fr.is_submitted = 1
             ORDER BY fr.created_at DESC LIMIT 1",
            [$clientId]
        );

        // Get service catalog
        $services = Database::fetchAll("SELECT * FROM service_catalog WHERE is_active = 1 ORDER BY category");

        // Build the prompt
        $prompt = "# Client Analysis Request\n\n";
        $prompt .= "## Client Info\n";
        $prompt .= "- Company: {$client['company_name']}\n";
        $prompt .= "- Contact: {$client['contact_name']}\n";
        $prompt .= "- Email: {$client['contact_email']}\n";
        $prompt .= "- Website: {$client['website']}\n";
        $prompt .= "- Temperature: {$client['temperature']}\n\n";

        if ($formResponse) {
            $responses = json_decode($formResponse['responses'], true) ?? [];
            $structure = json_decode($formResponse['structure'], true) ?? [];

            $prompt .= "## Questionnaire Responses\n\n";
            if (isset($structure['sections'])) {
                foreach ($structure['sections'] as $section) {
                    $prompt .= "### {$section['title']}\n";
                    foreach ($section['fields'] as $field) {
                        $answer = $responses[$field['id']] ?? 'Not answered';
                        if (is_array($answer)) $answer = implode(', ', $answer);
                        $prompt .= "- **{$field['label']}**: {$answer}\n";
                        if (isset($field['ai_hint'])) {
                            $prompt .= "  (AI Hint: {$field['ai_hint']}, Weight: {$field['ai_weight']})\n";
                        }
                    }
                    $prompt .= "\n";
                }
            }
        }

        $prompt .= "## Available Service Catalog\n\n";
        foreach ($services as $svc) {
            $prompt .= "- **{$svc['name']}** ({$svc['category']}): R\$ " .
                number_format((float)$svc['base_price_min'], 0, ',', '.') . " – R\$ " .
                number_format((float)$svc['base_price_max'], 0, ',', '.') .
                " | {$svc['typical_duration_days']} days | Difficulty: {$svc['technical_difficulty']}\n";
        }

        $prompt .= "\n## Instructions\n\n";
        $prompt .= "Analyze the client's responses and provide a structured analysis in JSON format:\n";
        $prompt .= "```json\n";
        $prompt .= "{\n";
        $prompt .= '  "diagnosis": "Detailed diagnostic of the client\'s current situation",'. "\n";
        $prompt .= '  "recommendations": ["Array of specific recommendations"],'. "\n";
        $prompt .= '  "risks": ["Array of identified risks"],'. "\n";
        $prompt .= '  "proposal_structure": {'. "\n";
        $prompt .= '    "phases": [{"title": "Phase name", "description": "...", "duration_days": 15, "services": ["service_name"], "value": 15000}]'. "\n";
        $prompt .= "  },\n";
        $prompt .= '  "pricing_range": {"min": 10000, "max": 50000, "currency": "BRL"},'. "\n";
        $prompt .= '  "execution_plan": "Timeline and milestones description",'. "\n";
        $prompt .= '  "confidence_score": 85'. "\n";
        $prompt .= "}\n";
        $prompt .= "```\n";
        $prompt .= "Consider the AI hints and weights for each question to prioritize your analysis.\n";
        $prompt .= "Match recommendations to available services from the catalog.\n";
        $prompt .= "Respond ONLY with valid JSON, no additional text.\n";

        return $prompt;
    }

    /**
     * Build an email prompt based on client context and tone.
     */
    public static function buildEmailPrompt(int $clientId, string $tone = 'formal', ?string $context = null): string
    {
        $client = Database::fetch("SELECT * FROM clients WHERE id = ?", [$clientId]);
        if (!$client) throw new \RuntimeException("Client not found: {$clientId}");

        $branding = Database::fetch("SELECT * FROM branding LIMIT 1");

        $prompt = "# Email Generation\n\n";
        $prompt .= "Generate a professional email in Portuguese (Brazil) for:\n";
        $prompt .= "- Client: {$client['contact_name']} ({$client['company_name']})\n";
        $prompt .= "- Company sending: " . ($branding['company_name'] ?? 'LLMInvoice') . "\n";
        $prompt .= "- Tone: {$tone}\n";
        if ($context) $prompt .= "- Context: {$context}\n";
        $prompt .= "\nRespond in JSON: {\"subject\": \"...\", \"body_html\": \"...\"}\n";
        $prompt .= "The body_html should be clean HTML suitable for email.\n";

        return $prompt;
    }
}
