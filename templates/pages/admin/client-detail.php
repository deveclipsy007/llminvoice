<?php
/**
 * Client detail page with tabs (overview, notes, AI, proposals).
 * Layout: admin
 * Data: $client, $column, $notes, $formResponses, $aiAnalyses, $proposals
 */
$tempColors = ['cold' => 'text-blue-400', 'warm' => 'text-amber-400', 'hot' => 'text-red-400'];
$tempClass = $tempColors[$client['temperature'] ?? 'warm'] ?? 'text-amber-400';
?>

<!-- Breadcrumb + Header -->
<div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
    <a href="/admin/clients" class="hover:text-white transition-colors"><?= __('clients_title') ?></a>
    <span>/</span>
    <span class="text-white"><?= e($client['contact_name']) ?></span>
</div>

<div class="flex flex-col lg:flex-row gap-6">
    <!-- Left: Client Info Card -->
    <div class="lg:w-80 flex-shrink-0 space-y-4">
        <div class="glass-panel p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-lime/20 to-lime/5 flex items-center justify-center text-xl font-bold text-lime">
                    <?= strtoupper(substr($client['contact_name'], 0, 1)) ?>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white"><?= e($client['contact_name']) ?></h2>
                    <?php if ($client['company_name']): ?>
                        <p class="text-sm text-text-secondary"><?= e($client['company_name']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <?php if ($client['contact_email']): ?>
                    <div class="flex items-center gap-2 text-text-secondary">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="truncate"><?= e($client['contact_email']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($client['contact_phone']): ?>
                    <div class="flex items-center gap-2 text-text-secondary">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span><?= e($client['contact_phone']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($client['website']): ?>
                    <div class="flex items-center gap-2 text-text-secondary">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                        <a href="<?= e($client['website']) ?>" target="_blank" class="truncate hover:text-lime transition-colors"><?= e($client['website']) ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full" style="background: <?= e($column['color'] ?? '#666') ?>"></span>
                    <span class="text-sm text-text-secondary"><?= e($column['name_' . \App\Core\App::locale()] ?? $column['name_pt'] ?? '-') ?></span>
                </div>
                <span class="text-sm font-semibold <?= $tempClass ?>"><?= __('temp_' . ($client['temperature'] ?? 'warm')) ?></span>
            </div>

            <div class="mt-4 flex gap-2">
                <a href="/admin/clients/<?= $client['id'] ?>/edit" class="flex-1 text-center px-3 py-2 text-sm font-medium text-white bg-white/5 rounded-xl hover:bg-white/10 border border-white/10 transition-all"><?= __('edit') ?></a>
                <button onclick="triggerAiAnalysis()" class="flex-1 text-center px-3 py-2 text-sm font-semibold text-black bg-lime rounded-xl hover:bg-lime-400 transition-colors flex items-center justify-center gap-1">
                    <div class="ai-orb w-2 h-2"></div>
                    <?= __('ai_analyze') ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Right: Content Tabs -->
    <div class="flex-1 min-w-0">
        <!-- Tabs -->
        <div class="flex gap-1 mb-4 overflow-x-auto">
            <?php $tabs = ['notes' => __('notes'), 'ai' => __('ai_title'), 'proposals' => __('proposals_title'), 'forms' => __('nav_forms')]; ?>
            <?php foreach ($tabs as $key => $label): ?>
                <button onclick="switchTab('<?= $key ?>')" data-tab="<?= $key ?>"
                    class="tab-btn px-4 py-2 text-sm font-medium rounded-xl transition-all whitespace-nowrap
                           <?= $key === 'notes' ? 'bg-lime/10 text-lime border border-lime/20' : 'text-text-secondary hover:text-white hover:bg-white/5 border border-transparent' ?>">
                    <?= $label ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Tab: Notes -->
        <div id="tab-notes" class="tab-content">
            <div class="glass-panel p-4 mb-4">
                <form id="note-form" class="flex gap-3">
                    <?= csrf_field() ?>
                    <select name="type" class="px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                        <option value="note"><?= __('note_type_note') ?></option>
                        <option value="call"><?= __('note_type_call') ?></option>
                        <option value="meeting"><?= __('note_type_meeting') ?></option>
                        <option value="follow_up"><?= __('note_type_follow_up') ?></option>
                    </select>
                    <input type="text" name="content" class="flex-1 px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-text-secondary focus:outline-none focus:border-lime/50" placeholder="<?= __('add_note') ?>" required>
                    <button type="submit" class="px-4 py-2 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors"><?= __('save') ?></button>
                </form>
            </div>

            <div class="space-y-3" id="notes-list">
                <?php foreach ($notes as $n): ?>
                    <div class="glass-panel-sm p-3 flex gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0 text-xs font-bold text-lime"><?= strtoupper(substr($n['user_name'] ?? 'U', 0, 1)) ?></div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-sm font-medium text-white"><?= e($n['user_name'] ?? '') ?></span>
                                <span class="text-xs text-text-secondary bg-white/5 px-2 py-0.5 rounded-full"><?= __('note_type_' . ($n['type'] ?? 'note')) ?></span>
                                <span class="text-xs text-text-secondary"><?= e($n['created_at'] ?? '') ?></span>
                            </div>
                            <p class="text-sm text-text-secondary"><?= e($n['content'] ?? '') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($notes)): ?>
                    <p class="text-sm text-text-secondary text-center py-6"><?= __('no_results') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab: AI -->
        <div id="tab-ai" class="tab-content hidden">
            <?php if (empty($aiAnalyses)): ?>
                <div class="glass-panel p-8 text-center">
                    <div class="ai-orb w-4 h-4 mx-auto mb-3"></div>
                    <p class="text-sm text-text-secondary mb-4"><?= __('ai_none') ?></p>
                    <button onclick="triggerAiAnalysis()" class="px-4 py-2 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors"><?= __('ai_analyze') ?></button>
                </div>
            <?php else: ?>
                <?php foreach ($aiAnalyses as $analysis): ?>
                    <div class="glass-panel p-5 mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-<?= $analysis['status'] === 'completed' ? 'emerald' : ($analysis['status'] === 'failed' ? 'red' : 'amber') ?>-500/10 text-<?= $analysis['status'] === 'completed' ? 'emerald' : ($analysis['status'] === 'failed' ? 'red' : 'amber') ?>-400 font-medium"><?= $analysis['status'] ?></span>
                                <span class="text-xs text-text-secondary"><?= $analysis['provider'] ?>/<?= $analysis['model'] ?></span>
                            </div>
                            <span class="text-xs text-text-secondary"><?= $analysis['processing_time_ms'] ?? '-' ?>ms</span>
                        </div>
                        <?php if ($analysis['diagnosis']): ?>
                            <h4 class="text-sm font-semibold text-white mb-2"><?= __('ai_diagnosis') ?></h4>
                            <p class="text-sm text-text-secondary mb-4"><?= nl2br(e($analysis['diagnosis'])) ?></p>
                        <?php endif; ?>
                        <?php if ($analysis['recommendations']): ?>
                            <h4 class="text-sm font-semibold text-white mb-2"><?= __('ai_recommendations') ?></h4>
                            <ul class="list-disc list-inside text-sm text-text-secondary space-y-1 mb-4">
                                <?php foreach (json_decode($analysis['recommendations'], true) ?? [] as $rec): ?>
                                    <li><?= e($rec) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <div class="flex items-center gap-4 text-xs text-text-secondary pt-3 border-t border-white/5">
                            <span>Tokens: <?= ($analysis['tokens_input'] ?? 0) + ($analysis['tokens_output'] ?? 0) ?></span>
                            <span>Custo: $<?= number_format((float)($analysis['cost_usd'] ?? 0), 4) ?></span>
                            <span><?= $analysis['created_at'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Tab: Proposals -->
        <div id="tab-proposals" class="tab-content hidden">
            <?php if (empty($proposals)): ?>
                <div class="glass-panel p-8 text-center">
                    <p class="text-sm text-text-secondary"><?= __('no_results') ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($proposals as $p): ?>
                    <div class="glass-panel p-4 mb-3 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-white">v<?= $p['version_number'] ?? 1 ?></span>
                            <span class="text-sm text-text-secondary ml-2">R$ <?= number_format((float)($p['total_value'] ?? 0), 0, ',', '.') ?></span>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-white/5 text-text-secondary"><?= $p['status'] ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Tab: Forms -->
        <div id="tab-forms" class="tab-content hidden">
            <?php if (empty($formResponses)): ?>
                <div class="glass-panel p-8 text-center">
                    <p class="text-sm text-text-secondary"><?= __('no_results') ?></p>
                </div>
            <?php else: ?>
                <?php foreach ($formResponses as $fr): ?>
                    <div class="glass-panel p-4 mb-3 flex items-center justify-between">
                        <div>
                            <span class="text-sm font-medium text-white"><?= e($fr['template_name'] ?? 'Form') ?></span>
                            <span class="text-xs text-text-secondary ml-3"><?= $fr['completion_pct'] ?>%</span>
                        </div>
                        <span class="text-xs text-text-secondary"><?= e($fr['created_at'] ?? '') ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function switchTab(name) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('bg-lime/10', 'text-lime', 'border-lime/20');
        b.classList.add('text-text-secondary', 'border-transparent');
    });
    document.getElementById('tab-' + name)?.classList.remove('hidden');
    const btn = document.querySelector(`[data-tab="${name}"]`);
    if (btn) {
        btn.classList.add('bg-lime/10', 'text-lime', 'border-lime/20');
        btn.classList.remove('text-text-secondary', 'border-transparent');
    }
}

async function triggerAiAnalysis() {
    const res = await fetch('/admin/ai/<?= $client['id'] ?>/analyze', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''}
    });
    const data = await res.json();
    if (data.success) { location.reload(); }
    else { alert(data.error || 'Error'); }
}

document.getElementById('note-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = new FormData(e.target);
    const res = await fetch('/admin/clients/<?= $client['id'] ?>/notes', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''},
        body: JSON.stringify({content: form.get('content'), type: form.get('type')}),
    });
    if ((await res.json()).success) location.reload();
});
</script>
