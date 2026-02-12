<?php
/**
 * Pagination partial.
 * Expects: $pagination (from Pagination class), $baseUrl.
 */
if (!isset($pagination) || $pagination->totalPages <= 1) return;
$p = $pagination;
?>
<nav class="flex items-center justify-between mt-6" aria-label="Pagination">
    <p class="text-sm text-text-secondary">
        <?= __('showing', [
            'from' => (($p->currentPage - 1) * $p->perPage) + 1,
            'to'   => min($p->currentPage * $p->perPage, $p->totalItems),
            'total' => $p->totalItems,
        ]) ?>
    </p>

    <div class="flex items-center gap-1">
        <?php if ($p->currentPage > 1): ?>
            <a href="<?= $baseUrl ?>?page=<?= $p->currentPage - 1 ?>"
               class="px-3 py-1.5 text-sm rounded-lg bg-white/5 text-text-secondary hover:bg-white/10 hover:text-white border border-white/5 transition-colors">
                ←
            </a>
        <?php endif; ?>

        <?php
        $start = max(1, $p->currentPage - 2);
        $end = min($p->totalPages, $p->currentPage + 2);
        ?>

        <?php if ($start > 1): ?>
            <a href="<?= $baseUrl ?>?page=1" class="px-3 py-1.5 text-sm rounded-lg bg-white/5 text-text-secondary hover:bg-white/10 border border-white/5 transition-colors">1</a>
            <?php if ($start > 2): ?>
                <span class="px-2 text-text-secondary">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
            <a href="<?= $baseUrl ?>?page=<?= $i ?>"
               class="px-3 py-1.5 text-sm rounded-lg border transition-colors
                      <?= $i === $p->currentPage
                          ? 'bg-lime text-black font-semibold border-lime'
                          : 'bg-white/5 text-text-secondary hover:bg-white/10 hover:text-white border-white/5' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($end < $p->totalPages): ?>
            <?php if ($end < $p->totalPages - 1): ?>
                <span class="px-2 text-text-secondary">...</span>
            <?php endif; ?>
            <a href="<?= $baseUrl ?>?page=<?= $p->totalPages ?>" class="px-3 py-1.5 text-sm rounded-lg bg-white/5 text-text-secondary hover:bg-white/10 border border-white/5 transition-colors"><?= $p->totalPages ?></a>
        <?php endif; ?>

        <?php if ($p->currentPage < $p->totalPages): ?>
            <a href="<?= $baseUrl ?>?page=<?= $p->currentPage + 1 ?>"
               class="px-3 py-1.5 text-sm rounded-lg bg-white/5 text-text-secondary hover:bg-white/10 hover:text-white border border-white/5 transition-colors">
                →
            </a>
        <?php endif; ?>
    </div>
</nav>
