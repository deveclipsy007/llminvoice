<?php
/**
 * Service catalog settings page.
 * Layout: admin
 * Data: $services
 */
?>
<div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
    <span class="text-white font-semibold"><?= __('settings_title') ?></span>
</div>

<!-- Settings Nav -->
<div class="flex gap-2 mb-6">
    <a href="/admin/settings" class="px-4 py-2 text-sm font-medium rounded-xl text-text-secondary hover:text-white hover:bg-white/5 border border-transparent transition-all"><?= __('settings_general') ?></a>
    <a href="/admin/settings/services" class="px-4 py-2 text-sm font-medium rounded-xl bg-lime/10 text-lime border border-lime/20"><?= __('settings_services') ?></a>
    <a href="/admin/settings/branding" class="px-4 py-2 text-sm font-medium rounded-xl text-text-secondary hover:text-white hover:bg-white/5 border border-transparent transition-all"><?= __('settings_branding') ?></a>
</div>

<div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold text-white"><?= __('settings_services') ?></h2>
    <button onclick="document.getElementById('service-modal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        <?= __('add_new') ?>
    </button>
</div>

<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase"><?= __('name') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase hidden md:table-cell"><?= __('category') ?></th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-secondary uppercase hidden sm:table-cell"><?= __('price_range') ?></th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-text-secondary uppercase"><?= __('status') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php foreach ($services as $s): ?>
                    <tr class="hover:bg-white/[.02] transition-colors">
                        <td class="px-4 py-3 font-medium text-white"><?= e($s['name']) ?></td>
                        <td class="px-4 py-3 text-text-secondary hidden md:table-cell"><?= e($s['category'] ?? '-') ?></td>
                        <td class="px-4 py-3 text-right text-text-secondary hidden sm:table-cell">
                            R$ <?= number_format((float)($s['base_price_min'] ?? 0), 0, ',', '.') ?> — <?= number_format((float)($s['base_price_max'] ?? 0), 0, ',', '.') ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-medium rounded-full <?= $s['is_active'] ? 'bg-emerald-500/10 text-emerald-400' : 'bg-white/5 text-text-secondary' ?>">
                                <?= $s['is_active'] ? __('active') : __('inactive') ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($services)): ?>
                    <tr><td colspan="4" class="px-4 py-8 text-center text-text-secondary"><?= __('no_results') ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Service Modal -->
<div id="service-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="glass-panel p-6 w-full max-w-md">
        <h3 class="text-lg font-bold text-white mb-4"><?= __('add_new') ?></h3>
        <form method="POST" action="/admin/settings/services" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('name') ?> *</label>
                <input type="text" name="name" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
            </div>
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('category') ?></label>
                <input type="text" name="category" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('price_min') ?></label>
                    <input type="number" name="base_price_min" value="0" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('price_max') ?></label>
                    <input type="number" name="base_price_max" value="0" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-lime bg-white/5 border-white/20 rounded focus:ring-lime/50">
                <label class="text-sm text-text-secondary"><?= __('active') ?></label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors"><?= __('save') ?></button>
                <button type="button" onclick="document.getElementById('service-modal').classList.add('hidden')" class="px-5 py-2.5 text-sm text-text-secondary bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all"><?= __('cancel') ?></button>
            </div>
        </form>
    </div>
</div>
