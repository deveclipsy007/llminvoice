<?php
/**
 * Diagnostic thank-you page.
 * Layout: landing
 */
?>
<section class="max-w-xl mx-auto py-20 px-4 text-center">
    <div class="glass-panel p-10">
        <!-- Success Icon -->
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-lime/10 flex items-center justify-center">
            <svg class="w-8 h-8 text-lime" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-white mb-3"><?= __('diag_thank_you_title') ?></h1>
        <p class="text-text-secondary mb-6 max-w-sm mx-auto"><?= __('diag_thank_you_text') ?></p>

        <a href="/" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white hover:bg-white/10 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <?= __('back_home') ?>
        </a>
    </div>
</section>
