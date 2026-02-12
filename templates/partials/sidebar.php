<?php
/**
 * Admin sidebar navigation.
 * Uses $currentUri to determine active state.
 */
$currentUri = $_SERVER['REQUEST_URI'] ?? '/admin';
$navItems = [
    [
        'label' => __('nav_dashboard'),
        'url'   => '/admin',
        'icon'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
        'match' => '/admin',
        'exact' => true,
    ],
    [
        'label' => __('nav_kanban'),
        'url'   => '/admin/kanban',
        'icon'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>',
        'match' => '/admin/kanban',
    ],
    [
        'label' => __('nav_clients'),
        'url'   => '/admin/clients',
        'icon'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
        'match' => '/admin/client',
    ],
    [
        'label' => __('nav_public_forms'),
        'url'   => '/admin/public-forms',
        'icon'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
        'match' => '/admin/public-forms',
    ],
    [
        'label' => __('nav_forms'),
        'url'   => '/admin/settings/forms',
        'icon'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
        'match' => '/admin/form-builder',
    ],
    'divider',
    [
        'label' => __('nav_settings'),
        'url'   => '/admin/settings',
        'icon'  => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        'match' => '/admin/settings',
    ],
];

/**
 * Checks if a nav item is "active" based on URL match.
 */
function isNavActive(array $item, string $currentUri): bool
{
    if (isset($item['exact']) && $item['exact']) {
        return rtrim($currentUri, '/') === rtrim($item['match'], '/');
    }
    return str_starts_with($currentUri, $item['match']);
}
?>

<aside id="sidebar"
       class="fixed inset-y-0 left-0 z-40 w-64 bg-dark-card/80 backdrop-blur-xl border-r border-white/5 transform transition-transform duration-300 lg:translate-x-0 -translate-x-full">

    <!-- Logo -->
    <div class="h-16 flex items-center gap-3 px-6 border-b border-white/5">
        <div class="w-8 h-8 rounded-lg bg-lime/20 flex items-center justify-center">
            <svg class="w-5 h-5 text-lime" fill="currentColor" viewBox="0 0 24 24">
                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <span class="text-lg font-bold text-white tracking-tight">LLM<span class="text-lime">Invoice</span></span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <?php foreach ($navItems as $item): ?>
            <?php if ($item === 'divider'): ?>
                <div class="my-3 mx-3 border-t border-white/5"></div>
                <?php continue; ?>
            <?php endif; ?>

            <?php $active = isNavActive($item, $currentUri); ?>
            <a href="<?= $item['url'] ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group
                      <?= $active
                          ? 'bg-lime/10 text-lime border border-lime/20'
                          : 'text-text-secondary hover:text-white hover:bg-white/5 border border-transparent' ?>">
                <span class="<?= $active ? 'text-lime' : 'text-text-secondary group-hover:text-white' ?> transition-colors">
                    <?= $item['icon'] ?>
                </span>
                <span><?= $item['label'] ?></span>
                <?php if ($active): ?>
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-lime animate-pulse"></span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- User info at bottom -->
    <div class="p-4 border-t border-white/5">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-lime/30 to-lime/10 flex items-center justify-center text-xs font-bold text-lime">
                <?= strtoupper(substr(\App\Core\Session::userName() ?? 'A', 0, 1)) ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate"><?= e(\App\Core\Session::userName() ?? '') ?></p>
                <p class="text-xs text-text-secondary truncate"><?= e(\App\Core\Session::userEmail() ?? '') ?></p>
            </div>
            <a href="/logout" class="p-1.5 rounded-lg text-text-secondary hover:text-danger hover:bg-danger/10 transition-colors" title="<?= __('logout') ?>">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </div>
</aside>

<!-- Mobile overlay -->
<div id="sidebar-overlay" class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm hidden lg:hidden" onclick="toggleSidebar()"></div>
