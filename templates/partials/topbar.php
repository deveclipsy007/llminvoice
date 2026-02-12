<?php
/**
 * Admin topbar partial.
 * Mobile menu toggle + search + locale + notification placeholder.
 */
?>
<header class="sticky top-0 z-20 h-16 bg-dark/80 backdrop-blur-xl border-b border-white/5 flex items-center justify-between px-4 lg:px-6">
    <!-- Left: Mobile toggle + Page title -->
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-text-secondary hover:text-white hover:bg-white/5 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <?php if (isset($pageTitle)): ?>
            <h1 class="text-lg font-semibold text-white hidden sm:block"><?= e($pageTitle) ?></h1>
        <?php endif; ?>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-3">
        <!-- Search toggle -->
        <button id="search-toggle" class="p-2 rounded-lg text-text-secondary hover:text-white hover:bg-white/5 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>

        <!-- Locale switcher -->
        <?php include __DIR__ . '/locale-switcher.php'; ?>

        <!-- User avatar (mobile) -->
        <div class="lg:hidden">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-lime/30 to-lime/10 flex items-center justify-center text-xs font-bold text-lime">
                <?= strtoupper(substr(\App\Core\Session::userName() ?? 'A', 0, 1)) ?>
            </div>
        </div>
    </div>
</header>

<!-- Search overlay (hidden by default) -->
<div id="search-overlay" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-start justify-center pt-24">
    <div class="w-full max-w-lg mx-4">
        <div class="glass-panel p-2">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="global-search"
                       class="w-full pl-12 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-text-secondary focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30 text-sm"
                       placeholder="<?= __('search') ?>"
                       autofocus>
            </div>
        </div>
        <p class="text-xs text-text-secondary text-center mt-3">ESC <?= __('close') ?></p>
    </div>
</div>
