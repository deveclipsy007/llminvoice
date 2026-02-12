<?php

declare(strict_types=1);

/**
 * Sanitize text input (strip tags, trim whitespace).
 */
function sanitize(?string $value): string
{
    if ($value === null) {
        return '';
    }

    return trim(strip_tags($value));
}

/**
 * Generate a URL-friendly slug.
 */
function slugify(string $text): string
{
    $text = mb_strtolower($text);

    // Replace accented characters
    $replacements = [
        'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a', 'ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'õ' => 'o', 'ô' => 'o', 'ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c', 'ñ' => 'n',
    ];
    $text = strtr($text, $replacements);

    // Replace non-alphanumeric with hyphens
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);

    return trim($text, '-');
}

/**
 * Truncate text to a maximum length.
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return mb_substr($text, 0, $length) . $suffix;
}

/**
 * Highlight search terms in text.
 */
function highlight(string $text, string $term): string
{
    if ($term === '') {
        return e($text);
    }

    $escaped = e($text);
    $pattern = '/(' . preg_quote(e($term), '/') . ')/i';

    return preg_replace($pattern, '<mark class="bg-lime/30 text-white rounded px-0.5">$1</mark>', $escaped);
}

/**
 * Generate an excerpt from text.
 */
function excerpt(string $text, int $length = 200): string
{
    $text = strip_tags($text);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    return truncate($text, $length);
}

/**
 * Convert newlines to <br> tags.
 */
function nl2br_safe(?string $text): string
{
    if ($text === null) {
        return '';
    }

    return nl2br(e($text));
}
