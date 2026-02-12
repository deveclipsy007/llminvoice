<?php
/**
 * Client form fill wizard.
 * Layout: client
 * Data: $client, $template, $structure, $responses, $responseId
 */
$sections = $structure['sections'] ?? [];
$totalSections = count($sections);
?>

<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-white mb-2"><?= __('form_title') ?></h1>
        <p class="text-sm text-text-secondary"><?= __('form_greeting', ['name' => e($client['contact_name'] ?? '')]) ?></p>
    </div>

    <!-- Progress Bar -->
    <div class="mb-6">
        <div class="flex items-center justify-between text-xs text-text-secondary mb-1.5">
            <span><?= __('progress') ?></span>
            <span id="progress-pct">0%</span>
        </div>
        <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
            <div id="progress-bar" class="h-full rounded-full transition-all duration-500" style="width: 0%; background: <?= e($brandColor) ?>"></div>
        </div>
    </div>

    <form id="wizard-form">
        <input type="hidden" name="template_id" value="<?= $template['id'] ?>">

        <!-- Wizard Steps -->
        <?php foreach ($sections as $si => $section): ?>
            <div class="wizard-step <?= $si > 0 ? 'hidden' : '' ?>" data-step="<?= $si ?>">
                <div class="glass-panel p-6 space-y-4">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold" style="background: <?= e($brandColor) ?>20; color: <?= e($brandColor) ?>"><?= $si + 1 ?></span>
                        <h2 class="text-lg font-semibold text-white"><?= e($section['title'] ?? '') ?></h2>
                    </div>
                    <?php if (!empty($section['description'])): ?>
                        <p class="text-sm text-text-secondary"><?= e($section['description']) ?></p>
                    <?php endif; ?>

                    <?php foreach ($section['fields'] ?? [] as $field): ?>
                        <?php $fid = $field['id'] ?? ''; $val = $responses[$fid] ?? ''; ?>
                        <div>
                            <label class="block text-sm font-medium text-text-secondary mb-1.5"><?= e($field['label'] ?? '') ?> <?= ($field['required'] ?? false) ? '*' : '' ?></label>

                            <?php if (($field['type'] ?? 'text') === 'text'): ?>
                                <input type="text" name="responses[<?= e($fid) ?>]" value="<?= e($val) ?>" <?= ($field['required'] ?? false) ? 'required' : '' ?>
                                    class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                            <?php elseif ($field['type'] === 'textarea'): ?>
                                <textarea name="responses[<?= e($fid) ?>]" rows="3" <?= ($field['required'] ?? false) ? 'required' : '' ?>
                                    class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50 resize-none"><?= e($val) ?></textarea>
                            <?php elseif ($field['type'] === 'select'): ?>
                                <select name="responses[<?= e($fid) ?>]" <?= ($field['required'] ?? false) ? 'required' : '' ?>
                                    class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:border-lime/50">
                                    <option value=""><?= __('select_option') ?></option>
                                    <?php foreach ($field['options'] ?? [] as $opt): ?>
                                        <option value="<?= e($opt) ?>" <?= $val === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($field['type'] === 'radio'): ?>
                                <div class="space-y-2">
                                    <?php foreach ($field['options'] ?? [] as $opt): ?>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="responses[<?= e($fid) ?>]" value="<?= e($opt) ?>" <?= $val === $opt ? 'checked' : '' ?>
                                                class="w-4 h-4 text-lime bg-white/5 border-white/20 focus:ring-lime/50">
                                            <span class="text-sm text-text-secondary"><?= e($opt) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($field['type'] === 'checkbox'): ?>
                                <div class="space-y-2">
                                    <?php $checkedVals = is_array($val) ? $val : []; ?>
                                    <?php foreach ($field['options'] ?? [] as $opt): ?>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="responses[<?= e($fid) ?>][]" value="<?= e($opt) ?>" <?= in_array($opt, $checkedVals) ? 'checked' : '' ?>
                                                class="w-4 h-4 text-lime bg-white/5 border-white/20 rounded focus:ring-lime/50">
                                            <span class="text-sm text-text-secondary"><?= e($opt) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($field['type'] === 'range'): ?>
                                <div class="flex items-center gap-3">
                                    <input type="range" name="responses[<?= e($fid) ?>]" min="<?= $field['min'] ?? 1 ?>" max="<?= $field['max'] ?? 10 ?>" value="<?= $val ?: ($field['default'] ?? 5) ?>"
                                        oninput="this.nextElementSibling.textContent=this.value"
                                        class="flex-1 h-2 bg-white/10 rounded-full appearance-none [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:w-5 [&::-webkit-slider-thumb]:h-5 [&::-webkit-slider-thumb]:rounded-full" style="--thumb-bg:<?= e($brandColor) ?>">
                                    <span class="text-sm font-semibold text-lime w-8 text-center"><?= $val ?: ($field['default'] ?? 5) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Navigation -->
        <div class="flex justify-between mt-6">
            <button type="button" id="prev-btn" onclick="wizardPrev()" class="hidden px-5 py-2.5 text-sm text-text-secondary bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition-all"><?= __('previous') ?></button>
            <div class="ml-auto flex gap-3">
                <button type="button" id="next-btn" onclick="wizardNext()" class="px-5 py-2.5 text-sm font-semibold text-black rounded-xl transition-colors" style="background:<?= e($brandColor) ?>"><?= __('next') ?></button>
                <button type="button" id="submit-btn" onclick="wizardSubmit()" class="hidden px-5 py-2.5 text-sm font-semibold text-black rounded-xl transition-colors" style="background:<?= e($brandColor) ?>"><?= __('submit') ?></button>
            </div>
        </div>
    </form>
</div>

<script>
const totalSteps = <?= $totalSections ?>;
let currentStep = 0;
const token = '<?= e($client['form_token'] ?? '') ?>';

function wizardPrev() { if (currentStep > 0) { currentStep--; updateWizard(); } }
function wizardNext() {
    const step = document.querySelector(`[data-step="${currentStep}"]`);
    const required = step.querySelectorAll('[required]');
    for (const el of required) { if (!el.value) { el.focus(); return; } }
    if (currentStep < totalSteps - 1) { currentStep++; updateWizard(); autosave(); }
}
function updateWizard() {
    document.querySelectorAll('.wizard-step').forEach((s, i) => { s.classList.toggle('hidden', i !== currentStep); });
    document.getElementById('prev-btn').classList.toggle('hidden', currentStep === 0);
    document.getElementById('next-btn').classList.toggle('hidden', currentStep === totalSteps - 1);
    document.getElementById('submit-btn').classList.toggle('hidden', currentStep !== totalSteps - 1);
    const pct = Math.round(((currentStep + 1) / totalSteps) * 100);
    document.getElementById('progress-bar').style.width = pct + '%';
    document.getElementById('progress-pct').textContent = pct + '%';
}
function getFormData() {
    const fd = new FormData(document.getElementById('wizard-form'));
    const obj = {}; for (const [k, v] of fd.entries()) { if (k.startsWith('responses')) { const m = k.match(/\[([^\]]+)\]/); if (m) obj[m[1]] = v; } }
    return obj;
}
async function autosave() {
    await fetch(`/form/${token}/autosave`, {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({responses: getFormData(), completion_pct: Math.round(((currentStep + 1) / totalSteps) * 100), template_id: <?= $template['id'] ?>})
    });
}
async function wizardSubmit() {
    const res = await fetch(`/form/${token}/submit`, {
        method: 'POST', headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({responses: getFormData(), template_id: <?= $template['id'] ?>})
    });
    const data = await res.json();
    if (data.success && data.redirect) window.location.href = data.redirect;
}
</script>
