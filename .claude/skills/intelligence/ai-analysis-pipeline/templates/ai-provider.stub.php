<?php

function callProvider(array $payload, array $config): array
{
    $ch = curl_init($config['api_url']);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => (int) ($config['timeout_seconds'] ?? 60),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . ($config['api_key'] ?? ''),
        ],
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error || $httpCode !== 200) {
        throw new RuntimeException('Provider error');
    }

    return json_decode((string) $response, true) ?: [];
}
