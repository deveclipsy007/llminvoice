<?php
/**
 * Public form submissions admin page.
 * Layout: admin
 * Data: $submissions, $pagination
 */
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white"><?= __('nav_public_forms') ?></h1>
    <div class="flex items-center gap-3">
        <a href="/admin/settings/forms" class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 text-white text-sm font-semibold rounded-xl border border-white/10 hover:bg-white/10 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <?= __('nav_forms') ?>
        </a>
        <span class="text-sm text-text-secondary"><?= count($submissions) ?> <?= __('submissions') ?></span>
    </div>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase"><?= __('contact_name') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase hidden md:table-cell"><?= __('company') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase hidden sm:table-cell"><?= __('email') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase hidden lg:table-cell"><?= __('stage') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase"><?= __('date') ?></th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-secondary uppercase"><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php foreach ($submissions as $sub): ?>
                    <tr class="hover:bg-white/[.02] transition-colors">
                        <td class="px-4 py-3 font-medium text-white"><?= e($sub['contact_name'] ?? '') ?></td>
                        <td class="px-4 py-3 text-text-secondary hidden md:table-cell"><?= e($sub['company_name'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-text-secondary hidden sm:table-cell"><?= e($sub['contact_email'] ?? '-') ?></td>
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full" style="background: <?= e($sub['column_color'] ?? '#666') ?>"></span>
                                <span class="text-text-secondary"><?= e($sub['column_name'] ?? '-') ?></span>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-text-secondary text-xs"><?= e(substr($sub['created_at'] ?? '', 0, 10)) ?></td>
                        <td class="px-4 py-3 text-right">
                            <a href="/admin/clients/<?= $sub['id'] ?>" class="text-xs text-lime hover:underline"><?= __('details') ?></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($submissions)): ?>
                    <tr><td colspan="6" class="px-4 py-8 text-center text-text-secondary"><?= __('no_results') ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../../partials/pagination.php'; ?>
