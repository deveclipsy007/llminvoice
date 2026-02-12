<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;

/**
 * AiService - Multi-provider AI service with fallback chain.
 * Groq → Claude → OpenAI
 */
class AiService
{
    private static array $providers = [];

    /**
     * Initialize providers from config.
     */
    private static function loadProviders(): void
    {
        if (!empty(self::$providers)) return;

        $config = config('ai');
        self::$providers = [
            'groq' => [
                'url'     => $config['groq']['url'] ?? 'https://api.groq.com/openai/v1/chat/completions',
                'key'     => $config['groq']['api_key'] ?? env('GROQ_API_KEY', ''),
                'model'   => $config['groq']['model'] ?? 'llama-3.3-70b-versatile',
                'enabled' => !empty(env('GROQ_API_KEY')),
            ],
            'claude' => [
                'url'     => $config['claude']['url'] ?? 'https://api.anthropic.com/v1/messages',
                'key'     => $config['claude']['api_key'] ?? env('CLAUDE_API_KEY', ''),
                'model'   => $config['claude']['model'] ?? 'claude-3-5-sonnet-20241022',
                'enabled' => !empty(env('CLAUDE_API_KEY')),
            ],
            'openai' => [
                'url'     => $config['openai']['url'] ?? 'https://api.openai.com/v1/chat/completions',
                'key'     => $config['openai']['api_key'] ?? env('OPENAI_API_KEY', ''),
                'model'   => $config['openai']['model'] ?? 'gpt-4o-mini',
                'enabled' => !empty(env('OPENAI_API_KEY')),
            ],
        ];
    }

    /**
     * Analyze a client based on form responses.
     * Returns analysis ID or throws.
     */
    public static function analyzeClient(int $clientId, int $triggeredBy): int
    {
        // Create pending analysis record
        $analysisId = Database::insert('ai_analyses', [
            'client_id'    => $clientId,
            'triggered_by' => $triggeredBy,
            'provider'     => 'pending',
            'model'        => 'pending',
            'status'       => 'processing',
            'created_at'   => date('Y-m-d H:i:s'),
        ]);

        try {
            // Build prompt
            $prompt = AiPromptBuilder::buildClientAnalysisPrompt($clientId);

            // Call AI with fallback
            $startTime = microtime(true);
            $result = self::callWithFallback($prompt);
            $elapsed = (int) ((microtime(true) - $startTime) * 1000);

            // Parse result
            $parsed = AiResultParser::parseAnalysis($result['content']);

            // Update analysis record
            Database::update('ai_analyses', $analysisId, [
                'provider'           => $result['provider'],
                'model'              => $result['model'],
                'status'             => 'completed',
                'diagnosis'          => $parsed['diagnosis'] ?? null,
                'recommendations'    => $parsed['recommendations'] ?? null,
                'risks'              => $parsed['risks'] ?? null,
                'proposal_structure' => $parsed['proposal_structure'] ?? null,
                'pricing_range'      => $parsed['pricing_range'] ?? null,
                'execution_plan'     => $parsed['execution_plan'] ?? null,
                'tokens_input'       => $result['tokens_input'] ?? 0,
                'tokens_output'      => $result['tokens_output'] ?? 0,
                'cost_usd'           => $result['cost_usd'] ?? 0,
                'processing_time_ms' => $elapsed,
                'raw_response'       => json_encode($result['raw'] ?? []),
            ]);

            return $analysisId;

        } catch (\Throwable $e) {
            Database::update('ai_analyses', $analysisId, [
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
                'provider'      => 'none',
                'model'         => 'none',
            ]);
            throw $e;
        }
    }

    /**
     * Generate email content via AI.
     */
    public static function generateEmail(int $clientId, string $tone = 'formal', ?string $context = null): array
    {
        $prompt = AiPromptBuilder::buildEmailPrompt($clientId, $tone, $context);
        $result = self::callWithFallback($prompt);
        return AiResultParser::parseEmail($result['content']);
    }

    /**
     * Call AI providers with fallback chain.
     */
    private static function callWithFallback(string $prompt): array
    {
        self::loadProviders();

        $errors = [];
        foreach (self::$providers as $name => $provider) {
            if (!$provider['enabled']) continue;

            try {
                return self::callProvider($name, $provider, $prompt);
            } catch (\Throwable $e) {
                $errors[] = "{$name}: {$e->getMessage()}";
                \App\Core\Logger::warning("AI provider {$name} failed: {$e->getMessage()}");
                continue;
            }
        }

        throw new \RuntimeException('All AI providers failed: ' . implode('; ', $errors));
    }

    /**
     * Call a specific AI provider via cURL.
     */
    private static function callProvider(string $name, array $provider, string $prompt): array
    {
        if ($name === 'claude') {
            return self::callClaude($provider, $prompt);
        }

        // OpenAI-compatible (Groq, OpenAI)
        $payload = json_encode([
            'model'       => $provider['model'],
            'messages'    => [
                ['role' => 'system', 'content' => 'You are a senior business consultant and technology analyst. Always respond in valid JSON format.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.3,
            'max_tokens'  => 4096,
        ]);

        $ch = curl_init($provider['url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $provider['key'],
            ],
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException("HTTP {$httpCode}: " . ($response ?: 'Empty response'));
        }

        $json = json_decode($response, true);
        if (!$json || !isset($json['choices'][0]['message']['content'])) {
            throw new \RuntimeException('Invalid response format');
        }

        return [
            'provider'     => $name,
            'model'        => $provider['model'],
            'content'      => $json['choices'][0]['message']['content'],
            'tokens_input' => $json['usage']['prompt_tokens'] ?? 0,
            'tokens_output' => $json['usage']['completion_tokens'] ?? 0,
            'cost_usd'     => self::estimateCost($name, $json['usage'] ?? []),
            'raw'          => $json,
        ];
    }

    /**
     * Call Claude (Anthropic API has different format).
     */
    private static function callClaude(array $provider, string $prompt): array
    {
        $payload = json_encode([
            'model'      => $provider['model'],
            'system'     => 'You are a senior business consultant and technology analyst. Always respond in valid JSON format.',
            'messages'   => [
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 4096,
        ]);

        $ch = curl_init($provider['url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: ' . $provider['key'],
                'anthropic-version: 2023-06-01',
            ],
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException("Claude HTTP {$httpCode}: " . ($response ?: 'Empty response'));
        }

        $json = json_decode($response, true);
        if (!$json || !isset($json['content'][0]['text'])) {
            throw new \RuntimeException('Invalid Claude response format');
        }

        return [
            'provider'     => 'claude',
            'model'        => $provider['model'],
            'content'      => $json['content'][0]['text'],
            'tokens_input' => $json['usage']['input_tokens'] ?? 0,
            'tokens_output' => $json['usage']['output_tokens'] ?? 0,
            'cost_usd'     => self::estimateCost('claude', $json['usage'] ?? []),
            'raw'          => $json,
        ];
    }

    /**
     * Estimate cost in USD based on provider and token usage.
     */
    private static function estimateCost(string $provider, array $usage): float
    {
        $rates = [
            'groq'   => ['input' => 0.00027, 'output' => 0.00027],
            'claude' => ['input' => 0.003, 'output' => 0.015],
            'openai' => ['input' => 0.00015, 'output' => 0.0006],
        ];

        $rate = $rates[$provider] ?? ['input' => 0, 'output' => 0];
        $inputTokens = $usage['prompt_tokens'] ?? $usage['input_tokens'] ?? 0;
        $outputTokens = $usage['completion_tokens'] ?? $usage['output_tokens'] ?? 0;

        return round(($inputTokens * $rate['input'] / 1000) + ($outputTokens * $rate['output'] / 1000), 6);
    }
}
