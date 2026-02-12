<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\App;

class AiTranslationService
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $config = App::config('ai');
        $this->apiKey = $config['providers']['groq']['api_key'] ?? '';
        $this->model = $config['providers']['groq']['model'] ?? 'llama-3.3-70b-versatile';
    }

    public function translateForm(array $structure): array
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('Groq API Key not configured.');
        }

        $prompt = $this->buildPrompt($structure);
        $response = $this->callGroq($prompt);
        
        return $this->parseResponse($response, $structure);
    }

    private function buildPrompt(array $structure): string
    {
        $json = json_encode($structure, JSON_PRETTY_PRINT);
        
        return <<<EOT
You are a professional translator for technical and business forms.
Your task is to translate the following JSON form structure into English (en) and Spanish (es).
You must return the SAME JSON structure, adding an "i18n" object to each section and field.

Rules:
1. Do NOT remove or modify any existing fields — only ADD "i18n" objects.
2. For each SECTION, add: "i18n": { "en": { "title": "...", "description": "..." }, "es": { "title": "...", "description": "..." } }
3. For each FIELD, ALWAYS add "label" translation. Also add "placeholder" if the field has one.
4. For fields with "options" array, ALSO add translated "options" array. The translated options array MUST have the EXACT SAME number of items as the original. Example:
   "i18n": { "en": { "label": "...", "options": ["opt1", "opt2"] }, "es": { "label": "...", "options": ["opt1", "opt2"] } }
5. Keep the tone professional and concise.
6. Return ONLY valid JSON, no markdown, no explanations, no comments.

Input JSON:
{$json}
EOT;
    }

    private function callGroq(string $prompt): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.groq.com/openai/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that outputs only valid JSON.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object']
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            throw new \RuntimeException('Groq API Error: ' . $err);
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
             throw new \RuntimeException('Invalid JSON response from Groq');
        }

        if (isset($data['error'])) {
             $msg = is_array($data['error']) ? ($data['error']['message'] ?? json_encode($data['error'])) : $data['error'];
             throw new \RuntimeException('Groq API Error: ' . $msg);
        }

        return $data['choices'][0]['message']['content'] ?? '{}';
    }

    private function parseResponse(string $jsonResponse, array $originalStructure): array
    {
        $translated = json_decode($jsonResponse, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Fallback: return original if AI fails to produce valid JSON
            return $originalStructure;
        }

        return $translated;
    }
}
