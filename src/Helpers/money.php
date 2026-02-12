<?php

declare(strict_types=1);

/**
 * Format monetary value.
 */
function format_money(float $value, string $currency = 'BRL'): string
{
    return match ($currency) {
        'BRL' => 'R$ ' . number_format($value, 2, ',', '.'),
        'USD' => '$ ' . number_format($value, 2, '.', ','),
        'EUR' => '€ ' . number_format($value, 2, ',', '.'),
        default => number_format($value, 2, '.', ',') . ' ' . $currency,
    };
}

/**
 * Format monetary range.
 */
function format_money_range(float $min, float $max, string $currency = 'BRL'): string
{
    if ($min === $max) {
        return format_money($min, $currency);
    }

    return format_money($min, $currency) . ' — ' . format_money($max, $currency);
}

/**
 * Format cost in USD (for AI cost tracking).
 */
function format_cost_usd(float $value): string
{
    if ($value < 0.01) {
        return '< $0.01';
    }

    return '$' . number_format($value, 4, '.', ',');
}
