<?php
/**
 * Form editor page.
 * Layout: admin
 * Data: $template, $errors
 */
$isEdit = $template !== null;
$structure = $isEdit ? json_decode($template['structure'] ?? '{"sections":[]}', true) : ['sections' => []];
?>

<div class="flex items-center gap-2 text-sm text-text-secondary mb-4">
    <a href="/admin/settings/forms" class="hover:text-white transition-colors"><?= __('nav_forms') ?></a>
    <span>/</span>
    <span class="text-white"><?= $isEdit ? __('edit') : __('create') ?></span>
</div>

<div class="max-w-6xl mx-auto">
    <header class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white"><?= $isEdit ? __('edit') . ': ' . e($template['name']) : __('client_forms_new') ?></h2>
            <p class="text-sm text-text-secondary">Configure a estrutura e os campos do formulário.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button type="button" onclick="translateForm()" id="translate-btn" class="px-5 py-2.5 text-sm font-semibold text-lime hover:text-white bg-lime/10 hover:bg-lime/20 rounded-xl border border-lime/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                <span class="btn-text">Traduzir com IA</span>
                <span class="btn-loader hidden">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </span>
            </button>
            <a href="/admin/settings/forms" class="px-5 py-2.5 text-sm font-semibold text-text-secondary hover:text-white bg-white/5 rounded-xl border border-white/10 transition-all"><?= __('cancel') ?></a>
            <button type="button" form="form-builder-editor-main" onclick="submitMainForm()" id="save-btn" class="px-6 py-2.5 bg-lime text-black text-sm font-bold rounded-xl hover:bg-lime-400 transition-all shadow-[0_0_15px_rgba(163,230,53,0.2)]">
                <span class="btn-text"><?= __('save') ?></span>
                <span class="btn-loader hidden">
                    <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </header>

    <form id="form-builder-editor-main" class="space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $template['id'] ?? '' ?>">

        <!-- Meta Config -->
        <div class="glass-panel p-6 border-white/5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-2"><?= __('service_name') ?> *</label>
                    <input type="text" name="name" value="<?= e($template['name'] ?? '') ?>" 
                           class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/20 focus:outline-none focus:border-lime/50 transition-all" required>
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-2"><?= __('status') ?></label>
                    <select name="is_active" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-lime/50 transition-all cursor-pointer">
                        <option value="1" <?= ($template['is_active'] ?? 1) == 1 ? 'selected' : '' ?>><?= __('active') ?></option>
                        <option value="0" <?= ($template['is_active'] ?? 1) == 0 ? 'selected' : '' ?>><?= __('inactive') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-2">Padrão</label>
                    <select name="is_default" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white focus:outline-none focus:border-lime/50 transition-all cursor-pointer">
                        <option value="0" <?= ($template['is_default'] ?? 0) == 0 ? 'selected' : '' ?>>Não</option>
                        <option value="1" <?= ($template['is_default'] ?? 0) == 1 ? 'selected' : '' ?>>Sim</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-2"><?= __('description') ?></label>
                    <textarea name="description" rows="2" 
                              class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-white/20 focus:outline-none focus:border-lime/50 transition-all"><?= e($template['description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Mode Tabs -->
        <div class="flex items-center gap-1 p-1 bg-white/5 rounded-2xl w-fit border border-white/10">
            <button type="button" onclick="setMode('visual')" id="tab-visual" 
                    class="px-6 py-2 rounded-xl text-sm font-semibold transition-all bg-lime text-black shadow-lg">
                Visual Builder
            </button>
            <button type="button" onclick="setMode('json')" id="tab-json" 
                    class="px-6 py-2 rounded-xl text-sm font-semibold transition-all text-text-secondary hover:text-white">
                JSON Editor
            </button>
        </div>

        <!-- Editor Areas -->
        <div id="visual-editor" class="space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">Seções do Formulário</h3>
                <button type="button" onclick="addSection()" class="flex items-center gap-2 px-4 py-2 bg-lime/10 text-lime text-xs font-bold rounded-xl hover:bg-lime/20 transition-all border border-lime/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Adicionar Seção
                </button>
            </div>
            
            <div id="gui-sections" class="space-y-8">
                <!-- Sections will be injected here -->
            </div>
        </div>

        <div id="json-editor" class="hidden">
            <div class="glass-panel p-1 border-white/10 overflow-hidden rounded-2xl">
                <div class="flex items-center justify-between px-4 py-2 bg-white/5 border-b border-white/10 text-[10px] uppercase tracking-widest font-bold text-text-secondary">
                    <span>Editor de Estrutura</span>
                    <button type="button" onclick="syncFromJSON()" class="text-lime hover:underline">Sincronizar com Visual</button>
                </div>
                <textarea id="json-structure" name="structure" rows="25" 
                          class="w-full px-6 py-5 bg-transparent text-sm text-lime font-mono focus:outline-none border-none resize-none"
                          oninput="validateJSON(this)"><?= e(json_encode($structure, JSON_PRETTY_PRINT)) ?></textarea>
            </div>
            <div id="json-error" class="mt-4 p-4 bg-danger/10 border border-danger/20 rounded-xl text-xs text-danger hidden">
                Estrutura JSON inválida. Verifique a sintaxe.
            </div>
        </div>
    </form>
</div>

<!-- Templates for JS -->
<template id="section-template">
    <div class="section-card glass-panel group p-0 overflow-hidden border-white/5 hover:border-white/10 transition-all" data-section-id="">
        <div class="flex items-center justify-between bg-white/2 p-5 border-b border-white/5">
            <div class="flex-1 mr-4">
                <input type="text" placeholder="Ex: Informações de Projeto" 
                       class="section-title w-full bg-transparent text-white font-bold border-none focus:ring-0 p-0 text-xl placeholder-white/10" 
                       oninput="syncToJSON()">
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="addField(this)" class="p-2 rounded-xl text-text-secondary hover:text-lime hover:bg-lime/10 transition-all border border-transparent hover:border-lime/20" title="Adicionar Campo">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </button>
                <button type="button" onclick="removeSection(this)" class="p-2 rounded-xl text-text-secondary hover:text-danger hover:bg-danger/10 transition-all border border-transparent hover:border-danger/20" title="Remover Seção">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
        <div class="fields-container p-6 space-y-4 bg-white/1">
            <p class="empty-fields-msg text-center py-8 text-xs text-text-secondary italic">Nenhum campo adicionado nesta seção.</p>
        </div>
    </div>
</template>

<template id="field-template">
    <div class="field-card bg-white/2 p-5 rounded-2xl relative group border border-white/5 hover:border-white/10 transition-all" data-field-id="">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
            <div class="lg:col-span-5">
                <label class="block text-[8px] uppercase tracking-[0.2em] font-bold text-text-secondary mb-2">Label do Campo</label>
                <input type="text" placeholder="Ex: Qual o seu desafio hoje?" 
                       class="field-label w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-lime/50 transition-all" 
                       oninput="syncToJSON()">
            </div>
            <div class="lg:col-span-3">
                <label class="block text-[8px] uppercase tracking-[0.2em] font-bold text-text-secondary mb-2">Tipo</label>
                <select class="field-type w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-text-secondary focus:outline-none focus:border-lime/50 transition-all cursor-pointer" 
                        onchange="syncToJSON(); toggleOptionsUI(this)">
                    <option value="text">Texto Curto</option>
                    <option value="textarea">Texto Longo</option>
                    <option value="email">Email</option>
                    <option value="tel">Telefone</option>
                    <option value="select">Dropdown</option>
                    <option value="radio">Múltipla Escolha</option>
                    <option value="checkbox">Checkboxes</option>
                    <option value="range">Escala (1-10)</option>
                </select>
            </div>
            <div class="lg:col-span-2 pt-8 flex items-center justify-center">
                <label class="flex items-center gap-3 cursor-pointer group/check">
                    <input type="checkbox" class="field-required w-5 h-5 text-lime bg-white/5 border-white/10 rounded-lg focus:ring-lime/30 transition-all" 
                           onchange="syncToJSON()">
                    <span class="text-[10px] text-text-secondary uppercase tracking-widest group-hover/check:text-white transition-colors">Obrigatório</span>
                </label>
            </div>
            <div class="lg:col-span-2 pt-8 flex justify-end">
                <button type="button" onclick="removeField(this)" class="px-4 py-2 rounded-xl text-text-secondary hover:text-danger hover:bg-danger/10 border border-transparent hover:border-danger/20 transition-all text-xs font-bold">
                    Remover
                </button>
            </div>
        </div>
        
        <!-- Options for select/radio/checkbox -->
        <div class="options-ui hidden mt-6 pt-6 border-t border-white/5 bg-black/20 -mx-5 -mb-5 px-5 pb-5 rounded-b-2xl">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-[9px] uppercase tracking-widest font-bold text-text-secondary">Opções da Lista</h4>
                <button type="button" onclick="addOptionToField(this)" class="text-[9px] text-lime uppercase tracking-widest font-bold hover:underline">Adicionar Opção</button>
            </div>
            <div class="options-list flex flex-wrap gap-2">
                <!-- Options injected here as tags -->
            </div>
        </div>
    </div>
</template>

<script>
const sectionsContainer = document.getElementById('gui-sections');
const jsonTextarea = document.getElementById('json-structure');
const jsonError = document.getElementById('json-error');

// Mode Management
function setMode(mode) {
    const visualEditor = document.getElementById('visual-editor');
    const jsonEditor = document.getElementById('json-editor');
    const tabVisual = document.getElementById('tab-visual');
    const tabJson = document.getElementById('tab-json');
    
    if (mode === 'visual') {
        syncFromJSON(); // Always sync when going to visual
        visualEditor.classList.remove('hidden');
        jsonEditor.classList.add('hidden');
        tabVisual.className = 'px-6 py-2 rounded-xl text-sm font-semibold transition-all bg-lime text-black shadow-lg';
        tabJson.className = 'px-6 py-2 rounded-xl text-sm font-semibold transition-all text-text-secondary hover:text-white';
    } else {
        syncToJSON(); // Always sync when going to JSON
        visualEditor.classList.add('hidden');
        jsonEditor.classList.remove('hidden');
        tabJson.className = 'px-6 py-2 rounded-xl text-sm font-semibold transition-all bg-lime text-black shadow-lg';
        tabVisual.className = 'px-6 py-2 rounded-xl text-sm font-semibold transition-all text-text-secondary hover:text-white';
    }
}

// GUI Rendering
function initGUI() {
    let structure;
    try {
        structure = JSON.parse(jsonTextarea.value);
    } catch (e) {
        structure = { sections: [] };
    }
    
    renderAll(structure);
}

function renderAll(structure) {
    sectionsContainer.innerHTML = '';
    if (structure.sections && structure.sections.length > 0) {
        structure.sections.forEach(renderSection);
    } else {
        addSection(); // Start with one if empty
    }
}

function renderSection(sectionData) {
    const template = document.getElementById('section-template');
    const clone = template.content.cloneNode(true);
    const card = clone.querySelector('.section-card');
    
    card.querySelector('.section-title').value = sectionData.title || '';
    
    const fieldsContainer = card.querySelector('.fields-container');
    if (sectionData.fields && sectionData.fields.length > 0) {
        fieldsContainer.querySelector('.empty-fields-msg').remove();
        sectionData.fields.forEach(field => renderFieldUI(fieldsContainer, field));
    }
    
    sectionsContainer.appendChild(clone);
}

function renderFieldUI(container, fieldData) {
    const template = document.getElementById('field-template');
    const clone = template.content.cloneNode(true);
    const card = clone.querySelector('.field-card');
    
    card.querySelector('.field-label').value = fieldData.label || '';
    card.querySelector('.field-type').value = fieldData.type || 'text';
    card.querySelector('.field-required').checked = fieldData.required || false;
    card.dataset.id = fieldData.id || 'field_' + Math.random().toString(36).substr(2, 9);
    
    if (['select', 'radio', 'checkbox'].includes(fieldData.type)) {
        const uiContainer = card.querySelector('.options-ui');
        uiContainer.classList.remove('hidden');
        const optList = card.querySelector('.options-list');
        if (fieldData.options) {
            fieldData.options.forEach(opt => renderOptionUI(optList, opt));
        }
    }
    
    container.appendChild(clone);
}

function renderOptionUI(container, value) {
    const tag = document.createElement('div');
    tag.className = 'flex items-center gap-2 px-3 py-1.5 bg-white/5 rounded-lg border border-white/10 text-xs text-white group/tag hover:border-white/20 transition-all';
    tag.innerHTML = `
        <span contenteditable="true" spellcheck="false" class="focus:outline-none focus:text-lime" onblur="syncToJSON()">${value}</span>
        <button type="button" onclick="this.parentElement.remove(); syncToJSON()" class="text-white/20 hover:text-danger p-0.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    container.appendChild(tag);
}

// Interactions
function addSection() {
    renderSection({ title: '', fields: [] });
    syncToJSON();
}

function removeSection(btn) {
    if (confirm('Deseja realmente remover esta seção e todos os seus campos?')) {
        btn.closest('.section-card').remove();
        syncToJSON();
    }
}

function addField(btn) {
    const container = btn.closest('.section-card').querySelector('.fields-container');
    const emptyMsg = container.querySelector('.empty-fields-msg');
    if (emptyMsg) emptyMsg.remove();
    
    renderFieldUI(container, { label: '', type: 'text', required: false });
    syncToJSON();
}

function removeField(btn) {
    const container = btn.closest('.fields-container');
    btn.closest('.field-card').remove();
    
    if (container.children.length === 0) {
        const msg = document.createElement('p');
        msg.className = 'empty-fields-msg text-center py-8 text-xs text-text-secondary italic';
        msg.textContent = 'Nenhum campo adicionado nesta seção.';
        container.appendChild(msg);
    }
    
    syncToJSON();
}

function addOptionToField(btn) {
    const list = btn.closest('.options-ui').querySelector('.options-list');
    renderOptionUI(list, 'Nova Opção');
    syncToJSON();
}

function toggleOptionsUI(select) {
    const card = select.closest('.field-card');
    const ui = card.querySelector('.options-ui');
    const list = card.querySelector('.options-list');
    
    if (['select', 'radio', 'checkbox'].includes(select.value)) {
        ui.classList.remove('hidden');
        if (list.children.length === 0) {
            renderOptionUI(list, 'Opção 1');
        }
    } else {
        ui.classList.add('hidden');
    }
}

// Bi-directional Sync
function syncToJSON() {
    const structure = { sections: [] };
    
    document.querySelectorAll('.section-card').forEach(sCard => {
        const section = {
            title: sCard.querySelector('.section-title').value,
            fields: []
        };
        
        sCard.querySelectorAll('.field-card').forEach(fCard => {
            const field = {
                id: fCard.dataset.id || ('f_' + Math.random().toString(36).substr(2, 5)),
                label: fCard.querySelector('.field-label').value,
                type: fCard.querySelector('.field-type').value,
                required: fCard.querySelector('.field-required').checked
            };
            
            if (['select', 'radio', 'checkbox'].includes(field.type)) {
                field.options = Array.from(fCard.querySelectorAll('.options-list span'))
                    .map(span => span.textContent.trim())
                    .filter(txt => txt.length > 0);
            }
            
            section.fields.push(field);
        });
        
        structure.sections.push(section);
    });
    
    jsonTextarea.value = JSON.stringify(structure, null, 4);
}

function syncFromJSON() {
    try {
        const structure = JSON.parse(jsonTextarea.value);
        renderAll(structure);
        jsonError.classList.add('hidden');
    } catch (e) {
        jsonError.classList.remove('hidden');
    }
}

function validateJSON(textarea) {
    try {
        JSON.parse(textarea.value);
        jsonError.classList.add('hidden');
    } catch (e) {
        jsonError.classList.remove('hidden');
    }
}

async function translateForm() {
    const btn = document.getElementById('translate-btn');
    const btnText = btn.querySelector('.btn-text');
    const btnLoader = btn.querySelector('.btn-loader');
    
    // Ensure we have the latest JSON
    if (!document.getElementById('visual-editor').classList.contains('hidden')) {
        syncToJSON();
    }
    
    // Validation
    try {
        JSON.parse(jsonTextarea.value);
    } catch (err) {
        alert('O JSON da estrutura está inválido. Corrija antes de traduzir.');
        setMode('json');
        return;
    }

    if (!confirm('AIA irá traduzir seu formulário para Inglês e Espanhol. Isso pode levar alguns segundos. Deseja continuar?')) {
        return;
    }

    btnText.classList.add('hidden');
    btnLoader.classList.remove('hidden');
    btn.disabled = true;

    try {
        const formData = new FormData();
        formData.append('structure', jsonTextarea.value);
        
        // Add CSRF token
        const csrfToken = document.querySelector('input[name="_csrf"]')?.value || document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            formData.append('_csrf', csrfToken);
        }
        
        const response = await fetch('/admin/form-builder/translate', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const result = await response.json();
        
        if (result.success) {
            jsonTextarea.value = JSON.stringify(result.structure, null, 4);
            syncFromJSON();
            alert('Tradução concluída com sucesso!');
        } else {
            alert(result.error || 'Erro ao traduzir o formulário.');
        }
    } catch (err) {
        console.error(err);
        alert('Ocorreu um erro ao conectar com o serviço de tradução.');
    } finally {
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
        btn.disabled = false;
    }
}

async function submitMainForm() {
    const form = document.getElementById('form-builder-editor-main');
    const btn = document.getElementById('save-btn');
    const btnText = btn.querySelector('.btn-text');
    const btnLoader = btn.querySelector('.btn-loader');

    // Final Validation
    try {
        JSON.parse(jsonTextarea.value);
    } catch (err) {
        alert('O JSON da estrutura está inválido. Corrija antes de salvar.');
        setMode('json');
        return;
    }

    btnText.classList.add('hidden');
    btnLoader.classList.remove('hidden');
    btn.disabled = true;

    const formData = new FormData(form);
    
    try {
        const response = await fetch('/admin/form-builder/save', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = '/admin/settings/forms';
        } else {
            alert(result.error || 'Erro ao salvar o modelo.');
        }
    } catch (err) {
        console.error(err);
        alert('Ocorreu um erro inesperado.');
    } finally {
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
        btn.disabled = false;
    }
}

// Initial Run
document.addEventListener('DOMContentLoaded', initGUI);

// Aesthetic tweaks
const style = document.createElement('style');
style.textContent = `
    .glass-panel { background: rgba(255, 255, 255, 0.02); backdrop-filter: blur(10px); }
    [contenteditable]:focus { outline: none; border-bottom: 2px solid #a3e635; }
    #json-structure::-webkit-scrollbar { width: 8px; }
    #json-structure::-webkit-scrollbar-track { background: transparent; }
    #json-structure::-webkit-scrollbar-thumb { background: rgba(163, 230, 53, 0.1); border-radius: 10px; }
    #json-structure::-webkit-scrollbar-thumb:hover { background: rgba(163, 230, 53, 0.2); }
`;
document.head.appendChild(style);
</script>
