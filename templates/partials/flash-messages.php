<?php
/**
 * Flash messages partial - auto-dismiss toasts.
 * Uses get_flash_messages() helper.
 */
$flashMessages = get_flash_messages();
if (empty($flashMessages)) return;
?>
<div id="flash-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 max-w-sm w-full">
    <?php foreach ($flashMessages as $flash): ?>
        <?php
        $bgClass = match($flash['type']) {
            'success' => 'bg-green-500/10 border-green-500/30 text-green-400',
            'error'   => 'bg-danger/10 border-danger/30 text-danger',
            'warning' => 'bg-amber-500/10 border-amber-500/30 text-amber-400',
            'info'    => 'bg-blue-500/10 border-blue-500/30 text-blue-400',
            default   => 'bg-white/10 border-white/20 text-white',
        };
        $icon = match($flash['type']) {
            'success' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            'error'   => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            'warning' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>',
            default   => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
        };
        ?>
        <div class="flash-toast glass-panel-sm <?= $bgClass ?> px-4 py-3 flex items-center gap-3 animate-slide-up border"
             onclick="this.remove()">
            <?= $icon ?>
            <span class="text-sm font-medium flex-1"><?= e($flash['message']) ?></span>
            <button class="opacity-50 hover:opacity-100 transition-opacity" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    <?php endforeach; ?>
</div>
