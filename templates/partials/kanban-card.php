<?php
/**
 * Kanban card partial.
 * Expects: $client (array with client data), $columnColor
 */
$tempColors = [
    'cold' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
    'warm' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
    'hot'  => 'bg-red-500/10 text-red-400 border-red-500/20',
];
$tempClass = $tempColors[$client['temperature'] ?? 'warm'] ?? $tempColors['warm'];
$tempLabel = __('temp_' . ($client['temperature'] ?? 'warm'));
?>

<div class="kanban-card glass-panel-sm p-3 cursor-grab active:cursor-grabbing group hover:border-lime/20 transition-all duration-200"
     data-client-id="<?= $client['id'] ?>"
     draggable="true">

    <!-- Header -->
    <div class="flex items-start justify-between gap-2 mb-2">
        <div class="min-w-0 flex-1">
            <h4 class="text-sm font-semibold text-white truncate"><?= e($client['contact_name'] ?? '') ?></h4>
            <?php if ($client['company_name']): ?>
                <p class="text-xs text-text-secondary truncate"><?= e($client['company_name']) ?></p>
            <?php endif; ?>
        </div>
        <span class="flex-shrink-0 inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full border <?= $tempClass ?>">
            <?= $tempLabel ?>
        </span>
    </div>

    <!-- Info -->
    <div class="space-y-1 mb-2">
        <?php if ($client['contact_email']): ?>
            <p class="text-xs text-text-secondary flex items-center gap-1.5 truncate">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <?= e($client['contact_email']) ?>
            </p>
        <?php endif; ?>
        <?php if ($client['assigned_name'] ?? null): ?>
            <p class="text-xs text-text-secondary flex items-center gap-1.5">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <?= e($client['assigned_name']) ?>
            </p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="flex items-center justify-between pt-2 border-t border-white/5">
        <span class="text-[10px] text-text-secondary"><?= e(substr($client['created_at'] ?? '', 0, 10)) ?></span>
        <a href="/admin/clients/<?= $client['id'] ?>"
           class="text-xs text-lime opacity-0 group-hover:opacity-100 transition-opacity hover:underline">
            <?= __('details') ?> →
        </a>
    </div>
</div>
