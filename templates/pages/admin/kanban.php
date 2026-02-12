<?php
/**
 * Kanban board page.
 * Layout: admin
 * Data: $columns, $search, $temperature
 */
?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white flex items-center gap-2">
            <div class="ai-orb w-3 h-3"></div>
            <?= __('kanban_title') ?>
        </h1>
        <p class="text-sm text-text-secondary mt-1"><?= __('kanban_drag_hint') ?></p>
    </div>

    <!-- Filters -->
    <div class="flex items-center gap-3">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text"
                   id="kanban-search"
                   value="<?= e($search ?? '') ?>"
                   class="pl-10 pr-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-text-secondary focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30 w-48 transition-all"
                   placeholder="<?= __('kanban_search') ?>">
        </div>

        <select id="kanban-filter-temp"
                class="px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30 transition-all">
            <option value=""><?= __('all') ?></option>
            <option value="cold" <?= ($temperature ?? '') === 'cold' ? 'selected' : '' ?>><?= __('kan_cold') ?></option>
            <option value="warm" <?= ($temperature ?? '') === 'warm' ? 'selected' : '' ?>><?= __('kan_warm') ?></option>
            <option value="hot"  <?= ($temperature ?? '') === 'hot' ? 'selected' : '' ?>><?= __('kan_hot') ?></option>
        </select>

        <a href="/admin/clients/create" class="flex items-center gap-2 px-4 py-2 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <?= __('clients_new') ?>
        </a>
    </div>
</div>

<!-- Kanban Board -->
<div id="kanban-board" class="flex gap-4 overflow-x-auto pb-4 snap-x" style="min-height: calc(100vh - 220px);">
    <?php foreach ($columns as $col): ?>
        <div class="kanban-column flex-shrink-0 w-72 snap-start"
             data-column-id="<?= $col['id'] ?>">

            <!-- Column Header -->
            <div class="flex items-center justify-between px-3 py-2 mb-3 rounded-xl bg-white/5 border border-white/5">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full" style="background-color: <?= e($col['color']) ?>"></div>
                    <h3 class="text-sm font-semibold text-white">
                        <?= e($col['name_' . \App\Core\App::locale()] ?? $col['name_pt']) ?>
                    </h3>
                </div>
                <span class="text-xs text-text-secondary bg-white/5 px-2 py-0.5 rounded-full"><?= count($col['clients']) ?></span>
            </div>

            <!-- Column Body (droppable area) -->
            <div class="kanban-drop-zone space-y-3 min-h-[200px] p-1 rounded-xl transition-colors"
                 data-column-id="<?= $col['id'] ?>">
                <?php if (empty($col['clients'])): ?>
                    <div class="kanban-empty flex flex-col items-center justify-center py-8 text-center">
                        <svg class="w-8 h-8 text-white/10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-xs text-text-secondary"><?= __('kanban_no_clients') ?></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($col['clients'] as $client): ?>
                        <?php include __DIR__ . '/../../partials/kanban-card.php'; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Transition blocked toast (hidden) -->
<div id="kanban-toast" class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 hidden">
    <div class="glass-panel px-5 py-3 border-danger/30 bg-danger/10 flex items-center gap-3 max-w-md">
        <svg class="w-5 h-5 text-danger flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <div id="kanban-toast-text" class="text-sm text-white"></div>
    </div>
</div>
