<?php
/**
 * General settings page.
 * Layout: admin
 * Data: $settings
 */
?>
<div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
    <span class="text-white font-semibold"><?= __('settings_title') ?></span>
</div>

<!-- Settings Nav -->
<div class="flex gap-2 mb-6">
    <a href="/admin/settings" class="px-4 py-2 text-sm font-medium rounded-xl bg-lime/10 text-lime border border-lime/20"><?= __('settings_general') ?></a>
    <a href="/admin/settings/services" class="px-4 py-2 text-sm font-medium rounded-xl text-text-secondary hover:text-white hover:bg-white/5 border border-transparent transition-all"><?= __('settings_services') ?></a>
    <a href="/admin/settings/branding" class="px-4 py-2 text-sm font-medium rounded-xl text-text-secondary hover:text-white hover:bg-white/5 border border-transparent transition-all"><?= __('settings_branding') ?></a>
</div>

<div class="max-w-2xl">
    <form method="POST" action="/admin/settings" class="glass-panel p-6 space-y-5">
        <?= csrf_field() ?>

        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('app_name') ?></label>
            <input type="text" name="app_name" value="<?= e($settings['app_name'] ?? 'LLMInvoice') ?>" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('timezone') ?></label>
                <select name="timezone" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                    <?php foreach (['America/Sao_Paulo', 'America/New_York', 'Europe/London', 'Europe/Lisbon', 'UTC'] as $tz): ?>
                        <option value="<?= $tz ?>" <?= ($settings['timezone'] ?? 'America/Sao_Paulo') === $tz ? 'selected' : '' ?>><?= $tz ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('default_locale') ?></label>
                <select name="default_locale" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                    <option value="pt" <?= ($settings['default_locale'] ?? 'pt') === 'pt' ? 'selected' : '' ?>>Português</option>
                    <option value="en" <?= ($settings['default_locale'] ?? 'pt') === 'en' ? 'selected' : '' ?>>English</option>
                    <option value="es" <?= ($settings['default_locale'] ?? 'pt') === 'es' ? 'selected' : '' ?>>Español</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('default_currency') ?></label>
                <select name="default_currency" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                    <option value="BRL" <?= ($settings['default_currency'] ?? 'BRL') === 'BRL' ? 'selected' : '' ?>>BRL (R$)</option>
                    <option value="USD" <?= ($settings['default_currency'] ?? 'BRL') === 'USD' ? 'selected' : '' ?>>USD ($)</option>
                    <option value="EUR" <?= ($settings['default_currency'] ?? 'BRL') === 'EUR' ? 'selected' : '' ?>>EUR (€)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('proposal_validity_days') ?></label>
                <input type="number" name="proposal_validity_days" value="<?= e($settings['proposal_validity_days'] ?? '30') ?>" min="1" max="365" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('ai_primary_provider') ?></label>
            <select name="ai_primary_provider" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                <option value="groq" <?= ($settings['ai_primary_provider'] ?? 'groq') === 'groq' ? 'selected' : '' ?>>Groq (llama)</option>
                <option value="claude" <?= ($settings['ai_primary_provider'] ?? 'groq') === 'claude' ? 'selected' : '' ?>>Claude (Anthropic)</option>
                <option value="openai" <?= ($settings['ai_primary_provider'] ?? 'groq') === 'openai' ? 'selected' : '' ?>>OpenAI (GPT)</option>
            </select>
        </div>

        <div class="pt-4 border-t border-white/5">
            <button type="submit" class="px-6 py-2.5 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors"><?= __('save') ?></button>
        </div>
    </form>
</div>
