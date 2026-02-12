<?php
/**
 * Locale switcher partial.
 * Renders PT/EN/ES language toggle.
 */
$currentLocale = \App\Core\App::locale();
$locales = [
    'pt' => 'PT',
    'en' => 'EN',
    'es' => 'ES',
];
?>
<div class="flex items-center gap-1 bg-dark-surface rounded-lg p-0.5 border border-white/5">
    <?php foreach ($locales as $code => $label): ?>
        <a href="/lang/<?= $code ?>"
           class="px-2 py-1 text-xs font-medium rounded-md transition-all duration-200
                  <?= $currentLocale === $code
                      ? 'bg-lime text-black'
                      : 'text-text-secondary hover:text-white hover:bg-white/5' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>
