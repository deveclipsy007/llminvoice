/**
 * LLMInvoice - Form Wizard (Multi-Step Form with Autosave)
 */
(function () {
    'use strict';

    let currentStep = 0;
    let totalSteps = 0;
    let autosaveTimer = null;
    let formToken = '';
    let isDirty = false;

    // =========================================================================
    // Initialize Wizard
    // =========================================================================
    function initWizard() {
        const wizard = document.getElementById('form-wizard');
        if (!wizard) return;

        formToken = wizard.dataset.token || '';
        const sections = wizard.querySelectorAll('.wizard-step');
        totalSteps = sections.length;

        if (totalSteps === 0) return;

        // Show first step
        showStep(0);
        updateProgressBar();
        updateStepIndicators();

        // Navigation buttons
        document.getElementById('btn-prev')?.addEventListener('click', prevStep);
        document.getElementById('btn-next')?.addEventListener('click', nextStep);
        document.getElementById('btn-submit')?.addEventListener('click', submitForm);

        // Step indicator clicks
        document.querySelectorAll('.step-indicator').forEach((ind, i) => {
            ind.addEventListener('click', () => {
                if (i <= getMaxVisitedStep()) {
                    goToStep(i);
                }
            });
        });

        // Autosave on input change
        wizard.addEventListener('input', () => {
            isDirty = true;
            clearTimeout(autosaveTimer);
            autosaveTimer = setTimeout(autosave, 2000);
        });

        // Warn on page leave if dirty
        window.addEventListener('beforeunload', (e) => {
            if (isDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    }

    // =========================================================================
    // Step Navigation
    // =========================================================================
    function showStep(index) {
        const sections = document.querySelectorAll('.wizard-step');
        sections.forEach((s, i) => {
            s.classList.toggle('hidden', i !== index);
            if (i === index) {
                s.classList.add('animate-fade-in');
            }
        });

        currentStep = index;
        updateNavButtons();
        updateProgressBar();
        updateStepIndicators();

        // Scroll to top of wizard
        document.getElementById('form-wizard')?.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Store max visited
        const maxVisited = getMaxVisitedStep();
        if (index > maxVisited) {
            sessionStorage.setItem('wizard_max_step', String(index));
        }
    }

    function nextStep() {
        if (currentStep >= totalSteps - 1) return;

        // Validate current step
        if (!validateStep(currentStep)) return;

        showStep(currentStep + 1);
    }

    function prevStep() {
        if (currentStep <= 0) return;
        showStep(currentStep - 1);
    }

    function goToStep(index) {
        if (index < 0 || index >= totalSteps) return;
        showStep(index);
    }

    function getMaxVisitedStep() {
        return parseInt(sessionStorage.getItem('wizard_max_step') || '0', 10);
    }

    // =========================================================================
    // UI Updates
    // =========================================================================
    function updateNavButtons() {
        const prevBtn = document.getElementById('btn-prev');
        const nextBtn = document.getElementById('btn-next');
        const submitBtn = document.getElementById('btn-submit');

        if (prevBtn) prevBtn.classList.toggle('invisible', currentStep === 0);
        if (nextBtn) nextBtn.classList.toggle('hidden', currentStep === totalSteps - 1);
        if (submitBtn) submitBtn.classList.toggle('hidden', currentStep !== totalSteps - 1);
    }

    function updateProgressBar() {
        const fill = document.getElementById('progress-fill');
        if (!fill) return;

        const pct = totalSteps > 1 ? ((currentStep + 1) / totalSteps) * 100 : 100;
        fill.style.width = `${pct}%`;

        const label = document.getElementById('progress-label');
        if (label) label.textContent = `${currentStep + 1} / ${totalSteps}`;
    }

    function updateStepIndicators() {
        document.querySelectorAll('.step-indicator').forEach((ind, i) => {
            ind.classList.remove('active', 'completed');
            if (i === currentStep) {
                ind.classList.add('active');
            } else if (i < currentStep) {
                ind.classList.add('completed');
            }
        });
    }

    // =========================================================================
    // Validation
    // =========================================================================
    function validateStep(stepIndex) {
        const step = document.querySelectorAll('.wizard-step')[stepIndex];
        if (!step) return true;

        const requiredFields = step.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach((field) => {
            clearFieldError(field);

            if (field.type === 'checkbox') {
                if (!field.checked) {
                    showFieldError(field, 'This field is required');
                    valid = false;
                }
            } else if (!field.value.trim()) {
                showFieldError(field, 'This field is required');
                valid = false;
            }

            // Email validation
            if (field.type === 'email' && field.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(field.value)) {
                    showFieldError(field, 'Please enter a valid email');
                    valid = false;
                }
            }
        });

        if (!valid) {
            // Focus first error
            const firstError = step.querySelector('.field-error');
            if (firstError) {
                firstError.closest('.form-group')?.querySelector('input, textarea, select')?.focus();
            }
        }

        return valid;
    }

    function showFieldError(field, message) {
        const group = field.closest('.form-group') || field.parentElement;
        field.classList.add('border-danger', 'focus:border-danger', 'focus:ring-danger/30');
        field.classList.remove('border-white/10', 'focus:border-lime', 'focus:ring-lime/30');

        const error = document.createElement('p');
        error.className = 'field-error text-danger text-xs mt-1';
        error.textContent = message;
        group.appendChild(error);
    }

    function clearFieldError(field) {
        const group = field.closest('.form-group') || field.parentElement;
        field.classList.remove('border-danger', 'focus:border-danger', 'focus:ring-danger/30');
        field.classList.add('border-white/10', 'focus:border-lime', 'focus:ring-lime/30');
        group.querySelectorAll('.field-error').forEach((e) => e.remove());
    }

    // =========================================================================
    // Autosave
    // =========================================================================
    async function autosave() {
        if (!formToken || !isDirty) return;

        const indicator = document.getElementById('autosave-indicator');
        if (indicator) {
            indicator.textContent = 'Saving...';
            indicator.classList.remove('hidden');
        }

        try {
            const formData = collectFormData();
            const result = await api.post(`/form/${formToken}/autosave`, {
                responses: formData,
            });

            isDirty = false;

            if (indicator) {
                indicator.textContent = `Saved (${result.completion_pct || 0}%)`;
                setTimeout(() => indicator.classList.add('hidden'), 2000);
            }

            // Update completion bar if exists
            const completionBar = document.getElementById('completion-bar');
            if (completionBar && result.completion_pct !== undefined) {
                completionBar.style.width = `${result.completion_pct}%`;
            }
        } catch (err) {
            if (indicator) {
                indicator.textContent = 'Save failed';
                indicator.classList.add('text-danger');
                setTimeout(() => {
                    indicator.classList.add('hidden');
                    indicator.classList.remove('text-danger');
                }, 3000);
            }
        }
    }

    // =========================================================================
    // Form Data Collection
    // =========================================================================
    function collectFormData() {
        const wizard = document.getElementById('form-wizard');
        if (!wizard) return {};

        const data = {};
        wizard.querySelectorAll('input, textarea, select').forEach((field) => {
            const id = field.name || field.id;
            if (!id || id === '_csrf') return;

            if (field.type === 'checkbox') {
                if (!data[id]) data[id] = [];
                if (field.checked) data[id].push(field.value);
            } else if (field.type === 'radio') {
                if (field.checked) data[id] = field.value;
            } else {
                data[id] = field.value;
            }
        });

        return data;
    }

    // =========================================================================
    // Submit
    // =========================================================================
    async function submitForm() {
        // Validate last step
        if (!validateStep(currentStep)) return;

        const submitBtn = document.getElementById('btn-submit');
        loading.start(submitBtn);

        try {
            // Trigger final autosave
            isDirty = false;
            const form = document.getElementById('form-wizard')?.closest('form');
            if (form) {
                form.submit();
            }
        } catch (err) {
            loading.stop(submitBtn);
            toast.error('Failed to submit form');
        }
    }

    // =========================================================================
    // Init
    // =========================================================================
    document.addEventListener('DOMContentLoaded', initWizard);
})();
