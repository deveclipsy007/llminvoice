/**
 * LLMInvoice - Public Diagnostic Wizard Logic
 * Handles dynamic steps, progress bar, and encouraging messages.
 */
(function () {
    'use strict';

    let currentStep = -1;
    let steps = [];
    // messages are loaded from PHP in the HTML

    function initWizard() {
        steps = Array.from(document.querySelectorAll('.wizard-step'));
        if (steps.length === 0) return;

        // Find the index of the landing step (data-step="-1")
        const landingIndex = steps.findIndex(s => s.dataset.step === "-1");

        // If landing page exists, start there (index 0 in the array because it's first in DOM)
        // Otherwise start at first question
        if (landingIndex !== -1) {
            currentStep = landingIndex;
        } else {
            currentStep = 0;
        }

        showStep(currentStep);
        setupNavigation();
    }

    function showStep(index) {
        // Hide all steps
        steps.forEach((step, i) => {
            if (i === index) {
                step.classList.remove('hidden');
                // Trigger animation
                requestAnimationFrame(() => {
                    step.classList.remove('opacity-0', 'translate-x-4');
                });
            } else {
                step.classList.add('hidden', 'opacity-0', 'translate-x-4');
            }
        });

        currentStep = index;
        updateUI();
    }

    function updateUI() {
        // Check if we are on landing page
        const isLanding = steps[currentStep]?.dataset.step === "-1";

        // Progress Header Visibility
        const headerContainer = document.querySelector('.max-w-3xl > .mb-12');
        if (headerContainer) {
            headerContainer.style.opacity = isLanding ? '0' : '1';
            headerContainer.style.pointerEvents = isLanding ? 'none' : 'auto';
            headerContainer.style.transition = 'opacity 0.5s ease';
        }

        // Progress Bar
        const progressFill = document.getElementById('diag-progress-fill');
        if (progressFill) {
            // Count only question steps for progress (exclude landing)
            const questionStepsCount = steps.length - 1; // -1 for landing
            const currentQuestionIndex = currentStep; // since landing is 0, first question is 1

            let percentage = 0;
            if (!isLanding) {
                percentage = (currentQuestionIndex / questionStepsCount) * 100;
            }
            progressFill.style.width = percentage + '%';
        }

        // Step Info
        const stepInfo = document.getElementById('diag-step-info');
        if (stepInfo && !isLanding) {
            // Adjust count to ignore landing page
            stepInfo.textContent = `Passo ${currentStep} de ${steps.length - 1}`;
        }

        // Header Message
        const msgEl = document.getElementById('encouraging-message');
        if (msgEl && !isLanding) {
            msgEl.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                // Adjust index for messages (start from 0 for first question)
                const msgIndex = Math.min(currentStep - 1, messages.length - 1);
                if (msgIndex >= 0) {
                    msgEl.textContent = messages[msgIndex];
                    msgEl.classList.remove('opacity-0', 'translate-y-2');
                }
            }, 300);
        }

        // Navigation Buttons (Bottom)
        const prevBtn = document.getElementById('diag-prev');
        const nextBtn = document.getElementById('diag-next');
        const submitBtn = document.getElementById('diag-submit');
        const navContainer = document.querySelector('.flex.items-center.justify-between.pt-4');

        if (navContainer) {
            navContainer.classList.toggle('hidden', isLanding);
        }

        if (prevBtn) {
            prevBtn.classList.toggle('hidden', currentStep <= 1); // Hide on first question (index 1)
        }

        if (nextBtn) {
            nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);
        }

        if (submitBtn) {
            submitBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
        }

        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function setupNavigation() {
        document.getElementById('diag-start')?.addEventListener('click', () => {
            showStep(currentStep + 1);
        });

        document.getElementById('diag-next')?.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                showStep(currentStep + 1);
            }
        });

        document.getElementById('diag-prev')?.addEventListener('click', () => {
            if (currentStep > 0) {
                showStep(currentStep - 1);
            }
        });

        document.getElementById('diag-wizard-form')?.addEventListener('submit', (e) => {
            const submitBtn = document.getElementById('diag-submit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Enviando...';
            }
        });
    }

    function validateStep(index) {
        const step = steps[index];
        const requiredFields = step.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach(field => {
            field.classList.remove('border-danger', 'ring-danger');

            if (!field.value.trim()) {
                valid = false;
                field.classList.add('border-danger', 'ring-1', 'ring-danger');
                field.classList.add('animate-shake');
                setTimeout(() => field.classList.remove('animate-shake'), 500);
            }
        });

        if (!valid) {
            step.querySelector('[required]:invalid')?.focus();
        }

        return valid;
    }

    // Initialize on DOM load
    document.addEventListener('DOMContentLoaded', initWizard);

    // Add CSS for shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .animate-shake { animation: shake 0.2s ease-in-out 0s 2; }
        .border-danger { border-color: #ef4444 !important; }
        .ring-danger { --tw-ring-color: rgba(239, 68, 68, 0.3); }
    `;
    document.head.appendChild(style);

})();
