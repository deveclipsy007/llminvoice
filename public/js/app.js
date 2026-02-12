/**
 * LLMInvoice - Global JavaScript Utilities
 */
(function () {
    'use strict';

    // =========================================================================
    // CSRF Token
    // =========================================================================
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // =========================================================================
    // AJAX Helper
    // =========================================================================
    window.api = {
        async request(url, options = {}) {
            const defaults = {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': csrfToken,
                    'Accept': 'application/json',
                },
            };

            if (options.body && !(options.body instanceof FormData)) {
                defaults.headers['Content-Type'] = 'application/json';
                options.body = JSON.stringify(options.body);
            }

            const config = {
                ...defaults,
                ...options,
                headers: { ...defaults.headers, ...options.headers },
            };

            try {
                const response = await fetch(url, config);
                const data = await response.json();

                if (!response.ok) {
                    throw { status: response.status, ...data };
                }

                return data;
            } catch (err) {
                if (err.status === 401) {
                    window.location.href = '/login';
                    return;
                }
                throw err;
            }
        },

        get(url) {
            return this.request(url, { method: 'GET' });
        },

        post(url, body = {}) {
            return this.request(url, { method: 'POST', body });
        },

        put(url, body = {}) {
            return this.request(url, { method: 'PUT', body });
        },

        delete(url) {
            return this.request(url, { method: 'DELETE' });
        },
    };

    // =========================================================================
    // Flash Messages Auto-Dismiss
    // =========================================================================
    function initFlashMessages() {
        document.querySelectorAll('[data-auto-dismiss]').forEach((el) => {
            const delay = parseInt(el.dataset.autoDismiss) || 5000;
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 300);
            }, delay);
        });

        // Dismiss button
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-dismiss]');
            if (btn) {
                const target = btn.closest('.flash-message') || btn.parentElement;
                target.style.opacity = '0';
                setTimeout(() => target.remove(), 300);
            }
        });
    }

    // =========================================================================
    // Modal System
    // =========================================================================
    window.modal = {
        open(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.add('flex');
            document.body.style.overflow = 'hidden';
            // Focus first input
            setTimeout(() => {
                const input = el.querySelector('input, textarea, select');
                if (input) input.focus();
            }, 100);
        },

        close(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.add('hidden');
            el.classList.remove('flex');
            document.body.style.overflow = '';
        },

        confirm(message, onConfirm) {
            const container = document.getElementById('modal-container');
            if (!container) return;

            container.innerHTML = `
                <div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
                    <div class="glass-panel p-6 max-w-md w-full mx-4 animate-slide-up">
                        <p class="text-white mb-6">${message}</p>
                        <div class="flex gap-3 justify-end">
                            <button onclick="modal.close('confirm-modal'); document.getElementById('modal-container').innerHTML='';" class="btn-secondary-sm">Cancelar</button>
                            <button id="confirm-btn" class="btn-lime-sm">Confirmar</button>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('confirm-btn').addEventListener('click', () => {
                modal.close('confirm-modal');
                container.innerHTML = '';
                if (onConfirm) onConfirm();
            });
        },
    };

    // Initialize modals from data attributes
    function initModals() {
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-open]');
            if (trigger) {
                e.preventDefault();
                modal.open(trigger.dataset.modalOpen);
            }

            const closer = e.target.closest('[data-modal-close]');
            if (closer) {
                e.preventDefault();
                modal.close(closer.dataset.modalClose);
            }

            // Close on backdrop click
            if (e.target.classList.contains('fixed') && e.target.querySelector('.glass-panel')) {
                const id = e.target.id;
                if (id) modal.close(id);
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed.flex.z-50').forEach((el) => {
                    if (el.id) modal.close(el.id);
                });
            }
        });
    }

    // =========================================================================
    // Dropdown Toggle
    // =========================================================================
    function initDropdowns() {
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-dropdown]');

            // Close all open dropdowns first
            if (!trigger) {
                document.querySelectorAll('.dropdown-menu.active').forEach((d) => {
                    d.classList.remove('active');
                    d.classList.add('hidden');
                });
                return;
            }

            e.preventDefault();
            const menuId = trigger.dataset.dropdown;
            const menu = document.getElementById(menuId);
            if (!menu) return;

            const isOpen = menu.classList.contains('active');

            // Close all
            document.querySelectorAll('.dropdown-menu.active').forEach((d) => {
                d.classList.remove('active');
                d.classList.add('hidden');
            });

            if (!isOpen) {
                menu.classList.add('active');
                menu.classList.remove('hidden');
            }
        });
    }

    // =========================================================================
    // Toast Notifications
    // =========================================================================
    window.toast = {
        show(message, type = 'success', duration = 4000) {
            const colors = {
                success: 'border-green-500 bg-green-500/10 text-green-400',
                error: 'border-danger bg-danger/10 text-danger',
                warning: 'border-amber-500 bg-amber-500/10 text-amber-400',
                info: 'border-blue-500 bg-blue-500/10 text-blue-400',
            };

            const el = document.createElement('div');
            el.className = `fixed top-4 right-4 z-[100] glass-panel-sm border-l-4 ${colors[type] || colors.info} px-4 py-3 max-w-sm animate-slide-up transition-all duration-300`;
            el.innerHTML = `<p class="text-sm">${message}</p>`;
            document.body.appendChild(el);

            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 300);
            }, duration);
        },

        success(msg) { this.show(msg, 'success'); },
        error(msg) { this.show(msg, 'error', 6000); },
        warning(msg) { this.show(msg, 'warning'); },
        info(msg) { this.show(msg, 'info'); },
    };

    // =========================================================================
    // Utility Functions
    // =========================================================================
    window.utils = {
        debounce(fn, delay = 300) {
            let timer;
            return function (...args) {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(this, args), delay);
            };
        },

        formatMoney(value, currency = 'BRL') {
            return new Intl.NumberFormat(currency === 'BRL' ? 'pt-BR' : 'en-US', {
                style: 'currency',
                currency: currency,
            }).format(value);
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        },

        copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                toast.success('Copied!');
            });
        },
    };

    // =========================================================================
    // Loading States
    // =========================================================================
    window.loading = {
        start(el) {
            if (!el) return;
            el.dataset.originalText = el.innerHTML;
            el.disabled = true;
            el.innerHTML = `
                <svg class="animate-spin w-4 h-4 inline mr-2" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/>
                    <path d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" fill="currentColor" class="opacity-75"/>
                </svg>
                Loading...
            `;
        },

        stop(el) {
            if (!el || !el.dataset.originalText) return;
            el.disabled = false;
            el.innerHTML = el.dataset.originalText;
            delete el.dataset.originalText;
        },
    };

    // =========================================================================
    // Initialize on DOM Ready
    // =========================================================================
    document.addEventListener('DOMContentLoaded', () => {
        initFlashMessages();
        initModals();
        initDropdowns();
    });
})();
