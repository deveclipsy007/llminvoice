<?php
/**
 * Dashboard page - Bento grid with metrics and activity.
 * Layout: admin
 * Data: $metrics, $columns, $activity
 */
?>

<!-- Welcome header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white"><?= __('welcome_back', ['name' => e(\App\Core\Session::userName() ?? '')]) ?></h1>
    <p class="text-sm text-text-secondary mt-1"><?= __('pipeline_overview') ?></p>
</div>

<!-- Metrics Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Active Clients -->
    <div class="glass-panel p-4 hover:border-lime/20 transition-colors group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <span class="text-xs text-text-secondary"><?= __('active_clients') ?></span>
        </div>
        <p class="text-3xl font-bold text-white"><?= $metrics['total_clients'] ?></p>
    </div>

    <!-- Leads Today -->
    <div class="glass-panel p-4 hover:border-lime/20 transition-colors group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-lime/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <span class="text-xs text-text-secondary"><?= __('leads_today') ?></span>
        </div>
        <p class="text-3xl font-bold text-white"><?= $metrics['leads_today'] ?></p>
    </div>

    <!-- Revenue -->
    <div class="glass-panel p-4 hover:border-lime/20 transition-colors group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs text-text-secondary"><?= __('total_revenue') ?></span>
        </div>
        <p class="text-3xl font-bold text-white">R$ <?= number_format($metrics['total_revenue'], 0, ',', '.') ?></p>
    </div>

    <!-- Conversion -->
    <div class="glass-panel p-4 hover:border-lime/20 transition-colors group">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs text-text-secondary"><?= __('conversion_rate') ?></span>
        </div>
        <p class="text-3xl font-bold text-white"><?= $metrics['conversion_rate'] ?>%</p>
    </div>
</div>

<!-- Pipeline + Activity row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <!-- Pipeline Overview (2 cols) -->
    <div class="lg:col-span-2 glass-panel p-5">
        <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
            <div class="ai-orb w-2 h-2"></div>
            <?= __('pipeline_overview') ?>
        </h3>

        <div class="space-y-3">
            <?php foreach ($columns as $col): ?>
                <?php $pct = $metrics['total_clients'] > 0 ? round(($col['client_count'] / $metrics['total_clients']) * 100) : 0; ?>
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full" style="background-color: <?= e($col['color']) ?>"></div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-white font-medium truncate"><?= e($col['name_' . \App\Core\App::locale()] ?? $col['name_pt']) ?></span>
                            <span class="text-text-secondary"><?= $col['client_count'] ?></span>
                        </div>
                        <div class="w-full bg-white/5 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full transition-all duration-500" style="width: <?= $pct ?>%; background-color: <?= e($col['color']) ?>"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="/admin/kanban" class="inline-flex items-center gap-2 text-sm text-lime hover:text-lime-400 mt-4 transition-colors">
            <?= __('view_all') ?>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="glass-panel p-5">
        <h3 class="text-sm font-semibold text-white mb-4"><?= __('recent_activity') ?></h3>

        <?php if (empty($activity)): ?>
            <p class="text-sm text-text-secondary"><?= __('no_results') ?></p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($activity as $item): ?>
                    <div class="flex gap-3 group">
                        <div class="mt-0.5 w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0">
                            <?php
                            $icon = match($item['type'] ?? 'note') {
                                'call'      => '<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>',
                                'meeting'   => '<svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
                                'follow_up' => '<svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                default     => '<svg class="w-4 h-4 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>',
                            };
                            echo $icon;
                            ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-white truncate">
                                <span class="font-medium"><?= e($item['user_name'] ?? '') ?></span>
                                <span class="text-text-secondary ml-1"><?= __('note_type_' . ($item['type'] ?? 'note')) ?></span>
                            </p>
                            <p class="text-xs text-text-secondary truncate mt-0.5"><?= e($item['contact_name'] ?? '') ?> — <?= e($item['company_name'] ?? '') ?></p>
                            <p class="text-xs text-text-secondary/60 mt-0.5"><?= e($item['created_at'] ?? '') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
    <a href="/admin/clients/create" class="glass-panel p-4 flex items-center gap-3 group hover:border-lime/20 transition-all">
        <div class="w-10 h-10 rounded-xl bg-lime/10 group-hover:bg-lime/20 flex items-center justify-center transition-colors">
            <svg class="w-5 h-5 text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-white"><?= __('clients_new') ?></p>
            <p class="text-xs text-text-secondary"><?= __('create') ?></p>
        </div>
    </a>
    <a href="/admin/kanban" class="glass-panel p-4 flex items-center gap-3 group hover:border-lime/20 transition-all">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 group-hover:bg-blue-500/20 flex items-center justify-center transition-colors">
            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-white"><?= __('nav_kanban') ?></p>
            <p class="text-xs text-text-secondary"><?= __('pipeline_overview') ?></p>
        </div>
    </a>
    <a href="/admin/public-forms" class="glass-panel p-4 flex items-center gap-3 group hover:border-lime/20 transition-all">
        <div class="w-10 h-10 rounded-xl bg-purple-500/10 group-hover:bg-purple-500/20 flex items-center justify-center transition-colors">
            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
            <p class="text-sm font-medium text-white"><?= __('nav_public_forms') ?></p>
            <p class="text-xs text-text-secondary"><?= __('public_forms_pending') ?></p>
        </div>
    </a>
</div>
