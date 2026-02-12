<!DOCTYPE html>
<html lang="<?= \App\Core\App::locale() ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? __('diag_title')) ?> — LLMInvoice</title>
    <meta name="description" content="<?= e($pageDescription ?? __('diag_subtitle')) ?>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">

    <!-- Open Graph -->
    <meta property="og:title" content="<?= e($pageTitle ?? __('diag_title')) ?>">
    <meta property="og:description" content="<?= e($pageDescription ?? __('diag_subtitle')) ?>">
    <meta property="og:type" content="website">
</head>
<body class="bg-dark font-sans antialiased min-h-screen flex flex-col">

    <!-- Landing Header -->
    <header class="bg-dark/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-8 h-8 rounded-lg bg-lime/20 flex items-center justify-center group-hover:bg-lime/30 transition-colors">
                    <svg class="w-5 h-5 text-lime" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold text-white tracking-tight">LLM<span class="text-lime">Invoice</span></span>
            </a>

            <div class="flex items-center gap-4">
                <?php include __DIR__ . '/../partials/locale-switcher.php'; ?>
                <a href="/login" class="btn-lime-sm px-4 py-2 rounded-xl text-sm font-semibold bg-lime text-black hover:bg-lime-400 transition-colors">
                    <?= __('login') ?>
                </a>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php include __DIR__ . '/../partials/flash-messages.php'; ?>

    <!-- Content -->
    <main class="flex-1">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/5 bg-dark-card/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-md bg-lime/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-lime" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-white">LLM<span class="text-lime">Invoice</span></span>
                </div>
                <p class="text-xs text-text-secondary">
                    <?= __('copyright', ['year' => date('Y')]) ?>
                </p>
            </div>
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
