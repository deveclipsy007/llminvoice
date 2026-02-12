<?php
/**
 * Proposal editor page.
 * Layout: admin
 * Data: $proposal
 */
$version = $proposal['current_version'] ?? [];
$content = json_decode($version['content'] ?? '{}', true);
$phases = $content['phases'] ?? [];
?>

<!-- Breadcrumb -->
<div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
    <a href="/admin/clients/<?= $proposal['client_id'] ?>" class="hover:text-white transition-colors"><?= e($proposal['contact_name'] ?? '') ?></a>
    <span>/</span>
    <span class="text-white"><?= __('proposal_edit') ?> #<?= $proposal['id'] ?></span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left: Proposal Content -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Phases -->
        <?php foreach ($phases as $i => $phase): ?>
            <div class="glass-panel p-5 phase-block" data-phase="<?= $i ?>">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-base font-semibold text-white"><?= __('phase') ?> <?= $i + 1 ?>: <?= e($phase['title'] ?? '') ?></h3>
                        <p class="text-sm text-text-secondary mt-1"><?= e($phase['description'] ?? '') ?></p>
                    </div>
                    <span class="text-xs text-text-secondary bg-white/5 px-2 py-1 rounded-lg"><?= $phase['duration_days'] ?? '?' ?> <?= __('days') ?></span>
                </div>
                <?php if (!empty($phase['services'])): ?>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <?php foreach ($phase['services'] as $svc): ?>
                            <span class="text-xs px-2 py-1 rounded-lg bg-lime/10 text-lime border border-lime/20"><?= e($svc) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($phase['value'])): ?>
                    <p class="text-sm font-semibold text-lime mt-3">R$ <?= number_format((float)$phase['value'], 0, ',', '.') ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <?php if (empty($phases)): ?>
            <div class="glass-panel p-8 text-center">
                <p class="text-sm text-text-secondary"><?= __('no_results') ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right: Sidebar -->
    <div class="space-y-4">
        <!-- Summary card -->
        <div class="glass-panel p-5">
            <h4 class="text-sm font-semibold text-white mb-3"><?= __('summary') ?></h4>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-text-secondary"><?= __('status') ?></dt><dd class="font-medium text-white"><?= $proposal['status'] ?></dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary"><?= __('version') ?></dt><dd class="font-medium text-white">v<?= $version['version_number'] ?? 1 ?></dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary"><?= __('total_value') ?></dt><dd class="font-bold text-lime">R$ <?= number_format((float)($version['total_value'] ?? 0), 0, ',', '.') ?></dd></div>
                <div class="flex justify-between"><dt class="text-text-secondary"><?= __('validity') ?></dt><dd class="font-medium text-white"><?= $version['validity_days'] ?? 30 ?> <?= __('days') ?></dd></div>
            </dl>
        </div>

        <!-- Actions -->
        <div class="glass-panel p-5 space-y-3">
            <h4 class="text-sm font-semibold text-white mb-2"><?= __('actions') ?></h4>
            <button onclick="downloadPdf()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm font-medium text-white hover:bg-white/10 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <?= __('download_pdf') ?>
            </button>
            <button onclick="sendEmail()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-lime text-black rounded-xl text-sm font-semibold hover:bg-lime-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <?= __('send_email') ?>
            </button>
        </div>

        <!-- Versions -->
        <div class="glass-panel p-5">
            <h4 class="text-sm font-semibold text-white mb-3"><?= __('versions') ?></h4>
            <div class="space-y-2">
                <?php foreach ($proposal['versions'] as $v): ?>
                    <div class="flex justify-between items-center text-sm p-2 rounded-lg <?= $v['id'] == $version['id'] ? 'bg-lime/10 border border-lime/20' : 'hover:bg-white/5' ?> transition-colors">
                        <span class="text-white font-medium">v<?= $v['version_number'] ?></span>
                        <span class="text-text-secondary">R$ <?= number_format((float)$v['total_value'], 0, ',', '.') ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
async function downloadPdf() {
    window.open('/admin/proposals/<?= $proposal['id'] ?>/pdf', '_blank');
}
async function sendEmail() {
    if (!confirm('<?= __('confirm_action') ?>')) return;
    const res = await fetch('/admin/proposals/<?= $proposal['id'] ?>/send', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''}
    });
    const data = await res.json();
    if (data.success) { alert('<?= __('proposal_email_sent') ?>'); location.reload(); }
    else { alert(data.error || 'Error'); }
}
</script>
