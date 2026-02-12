<?php
/**
 * Form builder page.
 * Layout: admin
 * Data: $templates
 */
?>
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-white"><?= __('nav_forms') ?></h1>
    <button onclick="createTemplate()" class="inline-flex items-center gap-2 px-4 py-2 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        <?= __('client_forms_new') ?>
    </button>
</div>

<!-- Template Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($templates as $t): ?>
        <div class="glass-panel p-5 group hover:border-lime/20 transition-all">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <h3 class="text-sm font-semibold text-white"><?= e($t['name']) ?></h3>
                    <p class="text-xs text-text-secondary mt-1"><?= e($t['description'] ?? '') ?></p>
                </div>
                <span class="inline-flex px-2 py-0.5 text-[10px] font-medium rounded-full <?= $t['is_active'] ? 'bg-emerald-500/10 text-emerald-400' : 'bg-white/5 text-text-secondary' ?>">
                    <?= $t['is_active'] ? __('active') : __('inactive') ?>
                </span>
            </div>
            <?php
                $structure = json_decode($t['structure'] ?? '{}', true);
                $sectionCount = count($structure['sections'] ?? []);
                $fieldCount = 0;
                foreach ($structure['sections'] ?? [] as $s) { $fieldCount += count($s['fields'] ?? []); }
            ?>
            <div class="flex items-center gap-4 text-xs text-text-secondary mt-3 pt-3 border-t border-white/5">
                <span><?= $sectionCount ?> <?= __('sections') ?></span>
                <span><?= $fieldCount ?> <?= __('fields') ?></span>
                <span class="ml-auto"><?= e(substr($t['created_at'] ?? '', 0, 10)) ?></span>
            </div>
            <div class="flex gap-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="editTemplate(<?= $t['id'] ?>)" class="flex-1 text-xs text-center py-1.5 bg-white/5 rounded-lg text-white hover:bg-white/10 transition-colors"><?= __('edit') ?></button>
                <button onclick="deleteTemplate(<?= $t['id'] ?>)" class="text-xs px-3 py-1.5 bg-danger/10 rounded-lg text-danger hover:bg-danger/20 transition-colors"><?= __('delete') ?></button>
            </div>
        </div>
    <?php endforeach; ?>

    <?php if (empty($templates)): ?>
        <div class="col-span-full glass-panel p-8 text-center">
            <p class="text-sm text-text-secondary"><?= __('no_results') ?></p>
        </div>
    <?php endif; ?>
</div>

<script>
function createTemplate() { window.location.href = '/admin/form-builder/new'; }
function editTemplate(id) { window.location.href = '/admin/form-builder/' + id; }
async function deleteTemplate(id) {
    if (!confirm('<?= __('confirm_delete') ?>')) return;
    await fetch('/admin/form-builder/' + id, {
        method: 'DELETE',
        headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''}
    });
    location.reload();
}
</script>
