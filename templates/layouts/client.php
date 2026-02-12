<!DOCTYPE html>
<html lang="<?= \App\Core\App::locale() ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Client Portal') ?> — <?= e($brandName ?? 'LLMInvoice') ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

    <?php if (isset($brandColor) && $brandColor): ?>
    <style>
        :root { --brand-primary: <?= e($brandColor) ?>; }
    </style>
    <?php endif; ?>
</head>
<body class="bg-dark font-sans antialiased min-h-screen">

    <!-- Client Header -->
    <header class="bg-dark-card/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <?php if (isset($brandLogo) && $brandLogo): ?>
                    <img src="<?= e($brandLogo) ?>" alt="<?= e($brandName ?? '') ?>" class="h-8">
                <?php else: ?>
                    <div class="w-8 h-8 rounded-lg bg-lime/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-lime" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight"><?= e($brandName ?? 'LLMInvoice') ?></span>
                <?php endif; ?>
            </div>

            <?php include __DIR__ . '/../partials/locale-switcher.php'; ?>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php include __DIR__ . '/../partials/flash-messages.php'; ?>

    <!-- Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/5 mt-auto">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-xs text-text-secondary">
            <?= __('powered_by') ?>
        </div>
    </footer>

    <script src="<?= asset('js/app.js') ?>"></script>
    <?php if (isset($pageScript)): ?>
        <script src="<?= asset('js/' . $pageScript) ?>"></script>
    <?php endif; ?>

    <script>
    setTimeout(() => {
        document.querySelectorAll('.flash-toast').forEach((toast, i) => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, i * 200);
        });
    }, 4000);
    </script>
</body>
</html>
