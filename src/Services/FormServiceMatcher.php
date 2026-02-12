<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

/**
 * FormServiceMatcher - Matches form responses to service catalog.
 */
class FormServiceMatcher
{
    /**
     * Match a client's form responses against service rules.
     * Returns array of matched services with scores.
     */
    public static function matchServices(int $clientId, int $templateId): array
    {
        // Get form responses
        $response = Database::fetch(
            "SELECT responses FROM form_responses WHERE client_id = ? AND form_template_id = ? AND is_submitted = 1 ORDER BY id DESC LIMIT 1",
            [$clientId, $templateId]
        );

        if (!$response) return [];

        $answers = json_decode($response['responses'], true) ?? [];

        // Get rules for this template
        $rules = Database::fetchAll(
            "SELECT fsr.*, sc.name as service_name, sc.category, sc.base_price_min, sc.base_price_max, sc.typical_duration_days
             FROM form_service_rules fsr
             JOIN service_catalog sc ON sc.id = fsr.service_catalog_id
             WHERE fsr.form_template_id = ? AND sc.is_active = 1
             ORDER BY fsr.priority DESC",
            [$templateId]
        );

        $matches = [];

        foreach ($rules as $rule) {
            $conditions = json_decode($rule['conditions'], true) ?? [];
            $operator = $rule['logic_operator'] ?? 'AND';

            $result = self::evaluateConditions($conditions, $answers, $operator);

            if ($result['matched']) {
                $matches[] = [
                    'service_id'   => $rule['service_catalog_id'],
                    'service_name' => $rule['service_name'],
                    'category'     => $rule['category'],
                    'price_min'    => $rule['base_price_min'],
                    'price_max'    => $rule['base_price_max'],
                    'duration'     => $rule['typical_duration_days'],
                    'score'        => $result['score'],
                    'priority'     => $rule['priority'],
                ];
            }
        }

        // Sort by score then priority
        usort($matches, fn($a, $b) => ($b['score'] <=> $a['score']) ?: ($b['priority'] <=> $a['priority']));

        return $matches;
    }

    /**
     * Evaluate conditions against answers.
     */
    private static function evaluateConditions(array $conditions, array $answers, string $operator): array
    {
        if (empty($conditions)) return ['matched' => true, 'score' => 50];

        $passed = 0;
        $total = count($conditions);

        foreach ($conditions as $condition) {
            $fieldId = $condition['field_id'] ?? '';
            $op = $condition['operator'] ?? 'contains';
            $value = $condition['value'] ?? '';
            $answer = $answers[$fieldId] ?? null;

            if ($answer === null) continue;

            if (is_array($answer)) $answer = implode(', ', $answer);
            $answer = strtolower((string) $answer);
            $value = strtolower((string) $value);

            $match = match($op) {
                'equals'       => $answer === $value,
                'not_equals'   => $answer !== $value,
                'contains'     => str_contains($answer, $value),
                'not_contains' => !str_contains($answer, $value),
                'greater_than' => is_numeric($answer) && (float)$answer > (float)$value,
                'less_than'    => is_numeric($answer) && (float)$answer < (float)$value,
                'in'           => in_array($answer, explode(',', $value)),
                default        => false,
            };

            if ($match) $passed++;
        }

        $matched = $operator === 'AND' ? $passed === $total : $passed > 0;
        $score = $total > 0 ? (int) round(($passed / $total) * 100) : 0;

        return ['matched' => $matched, 'score' => $score];
    }
}
