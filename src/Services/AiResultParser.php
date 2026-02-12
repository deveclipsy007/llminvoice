<?php

declare(strict_types=1);

namespace App\Services;

/**
 * AiResultParser - Extracts structured data from AI responses.
 */
class AiResultParser
{
    /**
     * Parse an AI analysis response into structured data.
     */
    public static function parseAnalysis(string $content): array
    {
        $json = self::extractJson($content);

        return [
            'diagnosis'          => $json['diagnosis'] ?? null,
            'recommendations'    => isset($json['recommendations']) ? json_encode($json['recommendations']) : null,
            'risks'              => isset($json['risks']) ? json_encode($json['risks']) : null,
            'proposal_structure' => isset($json['proposal_structure']) ? json_encode($json['proposal_structure']) : null,
            'pricing_range'      => isset($json['pricing_range']) ? json_encode($json['pricing_range']) : null,
            'execution_plan'     => $json['execution_plan'] ?? null,
        ];
    }

    /**
     * Parse an email generation response.
     */
    public static function parseEmail(string $content): array
    {
        $json = self::extractJson($content);

        return [
            'subject'   => $json['subject'] ?? 'Proposta Comercial',
            'body_html' => $json['body_html'] ?? $content,
        ];
    }

    /**
     * Extract JSON from AI response text (handles markdown code blocks).
     */
    private static function extractJson(string $content): array
    {
        $content = trim($content);

        // Try to extract from markdown code block
        if (preg_match('/```(?:json)?\s*\n([\s\S]*?)\n```/', $content, $matches)) {
            $content = trim($matches[1]);
        }

        // Try direct JSON parse
        $decoded = json_decode($content, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Try to find JSON object in the text
        if (preg_match('/\{[\s\S]*\}/', $content, $matches)) {
            $decoded = json_decode($matches[0], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // Fallback: return raw content as diagnosis
        return ['diagnosis' => $content];
    }
}
