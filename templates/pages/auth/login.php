<?php
/**
 * Login page template.
 * Layout: auth
 */
?>
<h2 class="text-xl font-bold text-white text-center mb-1"><?= __('login_title') ?></h2>
<p class="text-sm text-text-secondary text-center mb-8"><?= __('login_subtitle') ?></p>

<form method="POST" action="/login" class="space-y-5" id="login-form">
    <?= csrf_field() ?>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('email') ?></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-text-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
            </div>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= e(old('email', '')) ?>"
                   class="dark-input w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-text-secondary focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30 text-sm transition-all"
                   placeholder="admin@llminvoice.com"
                   required
                   autofocus>
        </div>
        <?php if ($errors['email'] ?? null): ?>
            <p class="text-xs text-danger mt-1"><?= e($errors['email']) ?></p>
        <?php endif; ?>
    </div>

    <!-- Password -->
    <div>
        <label for="password" class="block text-sm font-medium text-text-secondary mb-1.5"><?= __('password') ?></label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-text-secondary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <input type="password"
                   id="password"
                   name="password"
                   class="dark-input w-full pl-10 pr-12 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-text-secondary focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/30 text-sm transition-all"
                   placeholder="••••••••"
                   required>
            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-text-secondary hover:text-white transition-colors">
                <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
        <?php if ($errors['password'] ?? null): ?>
            <p class="text-xs text-danger mt-1"><?= e($errors['password']) ?></p>
        <?php endif; ?>
    </div>

    <!-- Submit -->
    <button type="submit"
            id="login-btn"
            class="w-full py-3 px-4 bg-lime text-black font-semibold rounded-xl hover:bg-lime-400 focus:outline-none focus:ring-2 focus:ring-lime/50 focus:ring-offset-2 focus:ring-offset-dark transition-all duration-200 text-sm flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        <?= __('login') ?>
    </button>
</form>

<!-- AI Branding flourish -->
<div class="mt-6 flex items-center justify-center gap-2 text-text-secondary">
    <div class="ai-orb w-2 h-2"></div>
    <span class="text-xs"><?= __('login_subtitle') ?></span>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
}

// Prevent double-submit
document.getElementById('login-form')?.addEventListener('submit', function() {
    const btn = document.getElementById('login-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg> <?= __("loading") ?>';
});
</script>
