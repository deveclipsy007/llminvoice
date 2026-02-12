<!DOCTYPE html>
<html lang="<?= \App\Core\App::locale() ?>" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($pageTitle ?? 'Dashboard') ?> — LLMInvoice</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>?v=<?= time() ?>">

    <!-- CSRF Meta -->
    <meta name="csrf-token" content="<?= csrf_token() ?>">
</head>
<body class="bg-dark text-white font-sans antialiased min-h-screen">

    <!-- Sidebar -->
    <?php include __DIR__ . '/../partials/sidebar.php'; ?>

    <!-- Main wrapper -->
    <div class="lg:ml-64 min-h-screen flex flex-col">

        <!-- Topbar -->
        <?php include __DIR__ . '/../partials/topbar.php'; ?>

        <!-- Flash messages -->
        <?php include __DIR__ . '/../partials/flash-messages.php'; ?>

        <!-- Page content -->
        <main class="flex-1 p-4 lg:p-6">
            <?= $content ?>
        </main>

        <!-- Footer -->
        <footer class="px-4 lg:px-6 py-4 border-t border-white/5 text-center text-xs text-text-secondary">
            <?= __('copyright', ['year' => date('Y')]) ?>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="<?= asset('js/app.js') ?>"></script>
    <?php if (isset($pageScript)): ?>
        <script src="<?= asset('js/' . $pageScript) ?>"></script>
    <?php endif; ?>

    <script>
    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Search overlay
    document.getElementById('search-toggle')?.addEventListener('click', () => {
        const overlay = document.getElementById('search-overlay');
        overlay.classList.toggle('hidden');
        if (!overlay.classList.contains('hidden')) {
            document.getElementById('global-search')?.focus();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.getElementById('search-overlay')?.classList.add('hidden');
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('search-overlay')?.classList.toggle('hidden');
            document.getElementById('global-search')?.focus();
        }
    });

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
