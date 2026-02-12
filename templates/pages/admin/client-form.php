<?php
/**
 * Client create/edit form.
 * Layout: admin
 * Data: $client, $columns, $users, $errors
 */
$isEdit = $client !== null;
?>
<div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
    <a href="/admin/clients" class="hover:text-white transition-colors"><?= __('clients_title') ?></a>
    <span>/</span>
    <span class="text-white"><?= $isEdit ? __('edit') : __('clients_new') ?></span>
</div>

<div class="max-w-2xl">
    <div class="glass-panel p-6">
        <h2 class="text-lg font-bold text-white mb-6"><?= $isEdit ? __('edit') . ': ' . e($client['contact_name']) : __('clients_new') ?></h2>

        <form method="POST" action="<?= $isEdit ? '/admin/clients/' . $client['id'] : '/admin/clients' ?>" class="space-y-5">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('contact_name') ?> *</label>
                    <input type="text" name="contact_name" value="<?= e($client['contact_name'] ?? old('contact_name', '')) ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('company') ?></label>
                    <input type="text" name="company_name" value="<?= e($client['company_name'] ?? old('company_name', '')) ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('email') ?></label>
                    <input type="email" name="contact_email" value="<?= e($client['contact_email'] ?? old('contact_email', '')) ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('phone') ?></label>
                    <input type="tel" name="contact_phone" value="<?= e($client['contact_phone'] ?? old('contact_phone', '')) ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('website') ?></label>
                    <input type="url" name="website" value="<?= e($client['website'] ?? old('website', '')) ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30" placeholder="https://">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('temperature') ?></label>
                    <select name="temperature" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                        <option value="cold" <?= ($client['temperature'] ?? 'warm') === 'cold' ? 'selected' : '' ?>><?= __('kan_cold') ?></option>
                        <option value="warm" <?= ($client['temperature'] ?? 'warm') === 'warm' ? 'selected' : '' ?>><?= __('kan_warm') ?></option>
                        <option value="hot" <?= ($client['temperature'] ?? 'warm') === 'hot' ? 'selected' : '' ?>><?= __('kan_hot') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('stage') ?></label>
                    <select name="pipeline_column_id" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                        <?php foreach ($columns as $col): ?>
                            <option value="<?= $col['id'] ?>" <?= ($client['pipeline_column_id'] ?? '') == $col['id'] ? 'selected' : '' ?>>
                                <?= e($col['name_' . \App\Core\App::locale()] ?? $col['name_pt']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('assigned_to') ?></label>
                    <select name="assigned_user_id" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                        <option value="">—</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= ($client['assigned_user_id'] ?? '') == $u['id'] ? 'selected' : '' ?>><?= e($u['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-white/5">
                <button type="submit" class="px-6 py-2.5 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors"><?= __('save') ?></button>
                <a href="/admin/clients" class="px-6 py-2.5 text-sm text-text-secondary hover:text-white bg-white/5 rounded-xl border border-white/10 transition-all"><?= __('cancel') ?></a>
            </div>
        </form>
    </div>
</div>
