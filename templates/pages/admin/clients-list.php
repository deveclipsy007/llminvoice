<?php
/**
 * Clients list page with table and pagination.
 * Layout: admin
 * Data: $clients, $pagination, $search, $temperature
 */
?>
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-white"><?= __('clients_title') ?></h1>
    <a href="/admin/clients/create" class="inline-flex items-center gap-2 px-4 py-2 bg-lime text-black text-sm font-semibold rounded-xl hover:bg-lime-400 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
        <?= __('clients_new') ?>
    </a>
</div>

<!-- Filters -->
<div class="glass-panel p-4 mb-4 flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="text" id="client-search" value="<?= e($search ?? '') ?>" class="w-full pl-10 pr-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-white placeholder-text-secondary focus:outline-none focus:border-lime/50" placeholder="<?= __('search') ?>">
    </div>
    <select id="temp-filter" class="px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
        <option value=""><?= __('all') ?></option>
        <option value="cold" <?= ($temperature ?? '') === 'cold' ? 'selected' : '' ?>><?= __('kan_cold') ?></option>
        <option value="warm" <?= ($temperature ?? '') === 'warm' ? 'selected' : '' ?>><?= __('kan_warm') ?></option>
        <option value="hot" <?= ($temperature ?? '') === 'hot' ? 'selected' : '' ?>><?= __('kan_hot') ?></option>
    </select>
</div>

<!-- Table -->
<div class="glass-panel overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-white/5">
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase tracking-wider"><?= __('contact_name') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase tracking-wider hidden md:table-cell"><?= __('company') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase tracking-wider hidden lg:table-cell"><?= __('email') ?></th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-text-secondary uppercase tracking-wider"><?= __('temperature') ?></th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-text-secondary uppercase tracking-wider hidden sm:table-cell"><?= __('stage') ?></th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-text-secondary uppercase tracking-wider"><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($clients)): ?>
                    <tr><td colspan="6" class="px-4 py-8 text-center text-text-secondary"><?= __('no_results') ?></td></tr>
                <?php else: ?>
                    <?php foreach ($clients as $c): ?>
                        <?php
                        $tempColors = [
                            'cold' => 'bg-blue-500/10 text-blue-400',
                            'warm' => 'bg-amber-500/10 text-amber-400',
                            'hot'  => 'bg-red-500/10 text-red-400',
                        ];
                        $cls = $tempColors[$c['temperature'] ?? 'warm'] ?? $tempColors['warm'];
                        ?>
                        <tr class="hover:bg-white/[.02] transition-colors">
                            <td class="px-4 py-3">
                                <a href="/admin/clients/<?= $c['id'] ?>" class="font-medium text-white hover:text-lime transition-colors"><?= e($c['contact_name'] ?? '') ?></a>
                            </td>
                            <td class="px-4 py-3 text-text-secondary hidden md:table-cell"><?= e($c['company_name'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-text-secondary hidden lg:table-cell"><?= e($c['contact_email'] ?? '-') ?></td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full <?= $cls ?>"><?= __('temp_' . ($c['temperature'] ?? 'warm')) ?></span>
                            </td>
                            <td class="px-4 py-3 hidden sm:table-cell">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full" style="background: <?= e($c['column_color'] ?? '#666') ?>"></span>
                                    <span class="text-text-secondary"><?= e($c['column_name'] ?? '-') ?></span>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="/admin/clients/<?= $c['id'] ?>" class="text-xs text-lime hover:underline"><?= __('details') ?></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php include __DIR__ . '/../../partials/pagination.php'; ?>

<script>
const searchInput = document.getElementById('client-search');
const tempFilter = document.getElementById('temp-filter');
let searchTimeout;

function applyFilters() {
    const params = new URLSearchParams();
    if (searchInput.value) params.set('search', searchInput.value);
    if (tempFilter.value) params.set('temperature', tempFilter.value);
    window.location.href = '/admin/clients' + (params.toString() ? '?' + params.toString() : '');
}

searchInput?.addEventListener('input', () => { clearTimeout(searchTimeout); searchTimeout = setTimeout(applyFilters, 500); });
tempFilter?.addEventListener('change', applyFilters);
</script>
