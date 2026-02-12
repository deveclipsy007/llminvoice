<?php

return [
    'default_provider' => env('AI_PROVIDER', 'groq'),

    'providers' => [
        'groq' => [
            'api_key'    => env('GROQ_API_KEY', ''),
            'base_url'   => 'https://api.groq.com/openai/v1',
            'model'      => 'llama-3.3-70b-versatile',
            'model_fast' => 'llama-3.1-8b-instant',
            'max_tokens' => 4096,
        ],
        'claude' => [
            'api_key'    => env('CLAUDE_API_KEY', ''),
            'base_url'   => 'https://api.anthropic.com/v1',
            'model'      => 'claude-sonnet-4-20250514',
            'max_tokens' => 4096,
            'version'    => '2023-06-01',
        ],
        'openai' => [
            'api_key'    => env('OPENAI_API_KEY', ''),
            'base_url'   => 'https://api.openai.com/v1',
            'model'      => 'gpt-4o',
            'max_tokens' => 4096,
        ],
    ],

    'fallback_order' => ['groq', 'claude', 'openai'],

    'cost_per_1k_tokens' => [
        'groq' => [
            'input'  => 0.00059,
            'output' => 0.00079,
        ],
        'claude' => [
            'input'  => 0.003,
            'output' => 0.015,
        ],
        'openai' => [
            'input'  => 0.0025,
            'output' => 0.010,
        ],
    ],

    'analysis' => [
        'timeout'    => 60,
        'max_retries' => 2,
    ],
];
