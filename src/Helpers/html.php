<?php

declare(strict_types=1);

/**
 * Check if a path is the current active route.
 */
function is_active(string $path, string $class = 'active'): string
{
    $current = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '/';
    $current = rtrim($current, '/') ?: '/';
    $path = rtrim($path, '/') ?: '/';

    if ($path === $current || str_starts_with($current, $path . '/')) {
        return $class;
    }

    return '';
}

/**
 * Status badge HTML.
 */
function status_badge(string $status): string
{
    $map = [
        'draft'     => ['badge-neutral', __('messages.status_draft')],
        'pending'   => ['badge-warning', __('messages.status_pending')],
        'review'    => ['badge-info', __('messages.status_review')],
        'approved'  => ['badge-success', __('messages.status_approved')],
        'sent'      => ['badge-info', __('messages.status_sent')],
        'accepted'  => ['badge-success', __('messages.status_accepted')],
        'rejected'  => ['badge-danger', __('messages.status_rejected')],
        'completed' => ['badge-success', __('messages.status_completed')],
        'failed'    => ['badge-danger', __('messages.status_failed')],
        'processing' => ['badge-warning', __('messages.status_processing')],
        'converted' => ['badge-success', __('messages.status_converted')],
    ];

    [$class, $label] = $map[$status] ?? ['badge-neutral', e($status)];

    return '<span class="' . $class . '">' . $label . '</span>';
}

/**
 * Temperature badge HTML.
 */
function temperature_badge(string $temperature): string
{
    $map = [
        'cold' => ['badge-cold', __('messages.temp_cold')],
        'warm' => ['badge-warm', __('messages.temp_warm')],
        'hot'  => ['badge-hot', __('messages.temp_hot')],
    ];

    [$class, $label] = $map[$temperature] ?? ['badge-neutral', e($temperature)];

    return '<span class="' . $class . '">' . $label . '</span>';
}

/**
 * AI confidence badge HTML.
 */
function ai_confidence_badge(float $score): string
{
    $percentage = round($score * 100);
    $color = match (true) {
        $score >= 0.8 => 'text-lime bg-lime/10',
        $score >= 0.5 => 'text-amber-400 bg-amber-400/10',
        default       => 'text-red-400 bg-red-400/10',
    };

    return '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium ' . $color . '">'
        . '<svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 110 20 10 10 0 010-20z"/><path d="M12 6v6l4 2"/></svg>'
        . $percentage . '%'
        . '</span>';
}

/**
 * Pipeline column badge.
 */
function pipeline_badge(string $columnSlug, string $columnName): string
{
    $colors = [
        'novo-lead'        => 'bg-blue-500/10 text-blue-400',
        'briefing-recebido' => 'bg-purple-500/10 text-purple-400',
        'analise-ia'       => 'bg-lime/10 text-lime',
        'proposta-enviada' => 'bg-amber-500/10 text-amber-400',
        'aceito'           => 'bg-green-500/10 text-green-400',
        'recusado'         => 'bg-red-500/10 text-red-400',
    ];

    $color = $colors[$columnSlug] ?? 'bg-white/10 text-white';

    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $color . '">'
        . e($columnName)
        . '</span>';
}

/**
 * Render pagination links.
 */
function pagination_links(\App\Core\Pagination $pagination, string $baseUrl): string
{
    if ($pagination->totalPages <= 1) {
        return '';
    }

    $separator = str_contains($baseUrl, '?') ? '&' : '?';
    $html = '<nav class="flex items-center gap-1">';

    // Previous
    if ($pagination->hasPrevious()) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . $pagination->previousPage() . '" class="btn-secondary-sm">&laquo;</a>';
    }

    // Page numbers
    foreach ($pagination->pages() as $page) {
        if ($page === '...') {
            $html .= '<span class="px-3 py-1 text-text-secondary">...</span>';
        } elseif ($page === $pagination->currentPage) {
            $html .= '<span class="btn-lime-sm">' . $page . '</span>';
        } else {
            $html .= '<a href="' . $baseUrl . $separator . 'page=' . $page . '" class="btn-secondary-sm">' . $page . '</a>';
        }
    }

    // Next
    if ($pagination->hasNext()) {
        $html .= '<a href="' . $baseUrl . $separator . 'page=' . $pagination->nextPage() . '" class="btn-secondary-sm">&raquo;</a>';
    }

    $html .= '</nav>';

    return $html;
}

/**
 * SVG icon helper (Lucide-style).
 */
function icon(string $name, string $class = 'w-5 h-5'): string
{
    $icons = [
        'home'         => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'layout'       => '<rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>',
        'users'        => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>',
        'settings'     => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"/>',
        'log-out'      => '<path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>',
        'plus'         => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
        'search'       => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',
        'zap'          => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        'file-text'    => '<path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>',
        'mail'         => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22 6 12 13 2 6"/>',
        'bar-chart-2'  => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',
        'shield'       => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
        'clipboard'    => '<path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>',
        'edit'         => '<path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>',
        'trash-2'      => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>',
        'eye'          => '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>',
        'check'        => '<polyline points="20 6 9 17 4 12"/>',
        'x'            => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'chevron-down' => '<polyline points="6 9 12 15 18 9"/>',
        'chevron-right' => '<polyline points="9 18 15 12 9 6"/>',
        'alert-circle' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'globe'        => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>',
        'download'     => '<path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
        'send'         => '<line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>',
    ];

    $svg = $icons[$name] ?? '';
    if (!$svg) {
        return '';
    }

    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">' . $svg . '</svg>';
}
