<?php
/**
 * Branding settings page.
 * Layout: admin
 * Data: $branding
 */
?>
<div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
    <span class="text-white font-semibold"><?= __('settings_title') ?></span>
</div>

<!-- Settings Nav -->
<div class="flex gap-2 mb-6">
    <a href="/admin/settings" class="px-4 py-2 text-sm font-medium rounded-xl text-text-secondary hover:text-white hover:bg-white/5 border border-transparent transition-all"><?= __('settings_general') ?></a>
    <a href="/admin/settings/services" class="px-4 py-2 text-sm font-medium rounded-xl text-text-secondary hover:text-white hover:bg-white/5 border border-transparent transition-all"><?= __('settings_services') ?></a>
    <a href="/admin/settings/branding" class="px-4 py-2 text-sm font-medium rounded-xl bg-lime/10 text-lime border border-lime/20"><?= __('settings_branding') ?></a>
</div>

<div class="max-w-2xl">
    <form method="POST" action="/admin/settings/branding" enctype="multipart/form-data" class="glass-panel p-6 space-y-5">
        <?= csrf_field() ?>

        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('company_name') ?></label>
            <input type="text" name="company_name" value="<?= e($branding['company_name'] ?? '') ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30">
        </div>

        <!-- Logo Upload -->
        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('logo') ?></label>
            <?php if (!empty($branding['logo_dark'])): ?>
                <div class="mb-3 p-3 bg-white/5 rounded-xl inline-block">
                    <img src="<?= e($branding['logo_dark']) ?>" alt="Logo" class="h-10 object-contain">
                </div>
            <?php endif; ?>
            <input type="file" name="logo_dark" accept="image/*"
                class="w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-white/5 file:text-white hover:file:bg-white/10 transition-all">
        </div>

        <!-- Colors -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('primary_color') ?></label>
                <div class="flex items-center gap-3">
                    <input type="color" name="primary_color" value="<?= e($branding['primary_color'] ?? '#C8FF00') ?>" class="w-10 h-10 rounded-xl cursor-pointer bg-transparent border-0">
                    <input type="text" value="<?= e($branding['primary_color'] ?? '#C8FF00') ?>" class="flex-1 px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50" readonly>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('secondary_color') ?></label>
                <div class="flex items-center gap-3">
                    <input type="color" name="secondary_color" value="<?= e($branding['secondary_color'] ?? '#1A1A1A') ?>" class="w-10 h-10 rounded-xl cursor-pointer bg-transparent border-0">
                    <input type="text" value="<?= e($branding['secondary_color'] ?? '#1A1A1A') ?>" class="flex-1 px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50" readonly>
                </div>
            </div>
        </div>

        <!-- Text fields -->
        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('email_footer') ?></label>
            <textarea name="email_footer_text" rows="3" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30 resize-none"><?= e($branding['email_footer_text'] ?? '') ?></textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('proposal_header') ?></label>
            <textarea name="proposal_header_text" rows="3" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30 resize-none"><?= e($branding['proposal_header_text'] ?? '') ?></textarea>
        </div>

        <!-- Preview -->
        <div class="pt-4 border-t border-white/5">
            <p class="text-sm font-medium text-text-secondary mb-3"><?= __('preview') ?></p>
            <div class="p-4 rounded-xl" style="background: <?= e($branding['secondary_color'] ?? '#1A1A1A') ?>; border: 1px solid <?= e($branding['primary_color'] ?? '#C8FF00') ?>30;">
                <div class="flex items-center gap-3">
                    <?php if (!empty($branding['logo_dark'])): ?>
                        <img src="<?= e($branding['logo_dark']) ?>" alt="Logo" class="h-8 object-contain">
                    <?php endif; ?>
                    <span class="text-lg font-bold" style="color: <?= e($branding['primary_color'] ?? '#C8FF00') ?>"><?= e($branding['company_name'] ?? 'LLMInvoice') ?></span>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-white/5">
            <button type="submit" class="px-6 py-2.5 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors"><?= __('save') ?></button>
        </div>
    </form>
</div>

<script>
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', (e) => {
        e.target.nextElementSibling.value = e.target.value;
    });
});
</script>
