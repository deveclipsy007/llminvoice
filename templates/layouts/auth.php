<!DOCTYPE html>
<html lang="<?= \App\Core\App::locale() ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? __('login')) ?> — LLMInvoice</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body class="bg-dark font-sans antialiased min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Background decoration -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 -left-32 w-96 h-96 bg-lime/5 rounded-full blur-[128px]"></div>
        <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-lime/3 rounded-full blur-[128px]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,transparent_0%,#050505_70%)]"></div>
    </div>

    <!-- Flash Messages -->
    <?php include __DIR__ . '/../partials/flash-messages.php'; ?>

    <!-- Auth Card -->
    <div class="relative z-10 w-full max-w-md mx-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-lime/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-lime" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white tracking-tight">LLM<span class="text-lime">Invoice</span></span>
            </div>
        </div>

        <!-- Card Content -->
        <div class="glass-panel p-8">
            <?= $content ?>
        </div>

        <!-- Locale Switcher -->
        <div class="flex justify-center mt-6">
            <?php include __DIR__ . '/../partials/locale-switcher.php'; ?>
        </div>

        <!-- Footer text -->
        <p class="text-center text-xs text-text-secondary mt-4">
            <?= __('copyright', ['year' => date('Y')]) ?>
        </p>
    </div>

    <script>
    // Auto-dismiss flash messages
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
