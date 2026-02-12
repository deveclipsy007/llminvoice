<?php
/**
 * Proposal client view (public via share token).
 * Layout: client
 * Data: $proposal, $version, $content, $token
 */
$phases = $content['phases'] ?? [];
?>

<div class="max-w-3xl mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-white mb-2"><?= __('proposal_view') ?></h1>
        <p class="text-sm text-text-secondary"><?= __('proposal_for', ['name' => e($proposal['contact_name'] ?? '')]) ?></p>
    </div>

    <!-- Phases -->
    <div class="space-y-4 mb-8">
        <?php foreach ($phases as $i => $phase): ?>
            <div class="glass-panel p-5">
                <h3 class="text-base font-semibold text-white mb-2"><?= __('phase') ?> <?= $i + 1 ?>: <?= e($phase['title'] ?? '') ?></h3>
                <p class="text-sm text-text-secondary mb-3"><?= e($phase['description'] ?? '') ?></p>
                <?php if (!empty($phase['services'])): ?>
                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php foreach ($phase['services'] as $svc): ?>
                            <span class="text-xs px-2 py-1 rounded-lg bg-lime/10 text-lime"><?= e($svc) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between text-sm">
                    <span class="text-text-secondary"><?= $phase['duration_days'] ?? '?' ?> <?= __('days') ?></span>
                    <span class="font-semibold text-lime">R$ <?= number_format((float)($phase['value'] ?? 0), 0, ',', '.') ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Summary -->
    <div class="glass-panel p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-white"><?= __('total_value') ?></h3>
            <span class="text-2xl font-bold text-lime">R$ <?= number_format((float)($version['total_value'] ?? 0), 0, ',', '.') ?></span>
        </div>
        <?php if ($version['payment_conditions']): ?>
            <p class="text-sm text-text-secondary"><strong><?= __('payment_conditions') ?>:</strong> <?= e($version['payment_conditions']) ?></p>
        <?php endif; ?>
        <p class="text-xs text-text-secondary mt-2"><?= __('validity') ?>: <?= $version['validity_days'] ?? 30 ?> <?= __('days') ?></p>
    </div>

    <!-- Accept/Reject buttons -->
    <?php if ($proposal['status'] === 'sent'): ?>
        <div class="flex gap-4">
            <button onclick="respondProposal('accept')" id="accept-btn"
                class="flex-1 py-3 bg-lime text-black font-semibold rounded-xl hover:bg-lime-400 transition-colors text-sm flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <?= __('proposal_accept') ?>
            </button>
            <button onclick="respondProposal('reject')" id="reject-btn"
                class="flex-1 py-3 bg-white/5 border border-white/10 text-white font-medium rounded-xl hover:bg-white/10 transition-colors text-sm">
                <?= __('proposal_reject') ?>
            </button>
        </div>
    <?php elseif ($proposal['status'] === 'accepted'): ?>
        <div class="glass-panel p-4 text-center bg-emerald-500/10 border-emerald-500/20">
            <p class="text-sm font-semibold text-emerald-400"><?= __('proposal_accepted') ?></p>
        </div>
    <?php elseif ($proposal['status'] === 'rejected'): ?>
        <div class="glass-panel p-4 text-center bg-red-500/10 border-red-500/20">
            <p class="text-sm font-semibold text-red-400"><?= __('proposal_rejected') ?></p>
        </div>
    <?php endif; ?>
</div>

<script>
async function respondProposal(action) {
    const feedback = action === 'reject' ? prompt('<?= __("proposal_feedback_prompt") ?>') : null;
    const res = await fetch('/proposal/<?= $token ?>/respond', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action, feedback})
    });
    const data = await res.json();
    if (data.success) location.reload();
    else alert(data.error || 'Error');
}
</script>
