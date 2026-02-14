<?php
/**
 * Public Diagnostic Form Wizard.
 * Layout: client
 * Data: $template
 */
use App\Core\App;

$structure = json_decode($template['structure'] ?? '{"sections":[]}', true);
$sections = $structure['sections'] ?? [];
$locale = App::locale();

// Helper to get localized value (supports strings AND arrays like options)
$t = function($obj, $key) use ($locale) {
    // Try i18n translation first
    if (isset($obj['i18n'][$locale][$key])) {
        $val = $obj['i18n'][$locale][$key];
        // For arrays (options): return only if non-empty array
        if (is_array($val) && !empty($val)) {
            return $val;
        }
        // For strings: return only if non-empty
        if (is_string($val) && $val !== '') {
            return $val;
        }
    }
    // Fallback to original value
    return $obj[$key] ?? (is_array($obj[$key] ?? null) ? [] : '');
};

$messages = [
    'pt' => ['Vamos começar!', 'Ótimo começo!', 'Continue assim!', 'Quase lá!', 'Última etapa!'],
    'en' => ['Let\'s start!', 'Great start!', 'Keep going!', 'Almost there!', 'Last step!'],
    'es' => ['¡Empecemos!', '¡Gran comienzo!', '¡Sigue así!', '¡Casi llegamos!', '¡Último paso!']
];

$encouragingMessages = $messages[$locale] ?? $messages['pt'];
?>
<script>
    const messages = <?= json_encode($encouragingMessages) ?>;
</script>

<style>
    .wizard-step { transition: opacity 0.5s ease, transform 0.5s ease; }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.3s ease-in-out; }
</style>

<section class="max-w-3xl mx-auto py-12 px-4 min-h-[80vh] flex flex-col">
    <!-- Progress Header -->
    <div class="mb-12 text-center">
        <div id="diag-progress-container" class="w-full h-1.5 bg-white/5 rounded-full overflow-hidden mb-4 max-w-md mx-auto">
            <div id="diag-progress-fill" class="h-full bg-lime transition-all duration-700 ease-out" style="width: 0%"></div>
        </div>
        
        <div id="wizard-header-content" class="mb-8">
            <h1 id="encouraging-message" class="text-2xl font-bold text-white mb-2 transition-all duration-500 opacity-0 transform translate-y-2">
                <?= $encouragingMessages[0] ?>
            </h1>
            <p id="diag-step-info" class="text-xs text-text-secondary uppercase tracking-[0.2em]">
                <?= __('step') ?> 1 <?= __('of') ?> <?= count($sections) + 1 ?>
            </p>
        </div>
    </div>

    <!-- Wizard Body -->
    <div class="flex-1 relative">
        <form id="diag-wizard-form" method="POST" action="/diagnostico" class="space-y-8">
            <?= csrf_field() ?>
            <input type="hidden" name="template_id" value="<?= $template['id'] ?>">
            <!-- Honeypot anti-spam (hidden from real users) -->
            <div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">
                <input type="text" name="website_url" value="" tabindex="-1" autocomplete="off">
            </div>

            <!-- Landing Step -->
            <div class="wizard-step transition-all duration-500" data-step="-1">
                <div class="flex flex-col items-center justify-center min-h-[60vh] text-center space-y-8">
                    
                    <!-- Language Selector -->
                    <div class="flex items-center gap-4 mb-8">
                        <?php foreach(['pt', 'en', 'es'] as $lang): ?>
                            <a href="?lang=<?= $lang ?>" class="px-3 py-1 text-sm font-semibold rounded-full border transition-all <?= $locale === $lang ? 'bg-lime/10 text-lime border-lime' : 'border-white/10 text-text-secondary hover:text-white hover:border-white/30' ?>">
                                <?= strtoupper($lang) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <div class="space-y-6 max-w-3xl glass-panel p-10 rounded-3xl border-white/5 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-lime/50 to-transparent"></div>
                        
                        <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-4 whitespace-nowrap">
                            <?= __('diag_welcome_title') ?>
                        </h1>
                        <p class="text-lg text-text-secondary leading-relaxed">
                            <?= __('diag_welcome_subtitle') ?>
                        </p>

                        <div class="pt-8">
                            <button type="button" id="diag-start" class="group relative px-8 py-3 bg-lime text-black font-semibold rounded-full hover:bg-lime/90 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-lime/25 transform hover:-translate-y-0.5">
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    <?= __('diag_start_btn') ?>
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Dynamic Steps from Sections -->
            <?php foreach ($sections as $si => $section): ?>
                <div class="wizard-step <?= $si === 0 ? '' : 'hidden opacity-0 translate-x-4' ?> transition-all duration-500" data-step="<?= $si ?>">
                    <div class="glass-panel p-8 border-white/5 rounded-3xl relative overflow-hidden">
                        <h2 class="text-xl font-bold text-white mb-2"><?= e($t($section, 'title')) ?></h2>
                        <?php if(!empty($section['description'])): ?>
                            <p class="text-sm text-text-secondary mb-6"><?= e($t($section, 'description')) ?></p>
                        <?php endif; ?>

                        <div class="space-y-6">
                            <?php foreach ($section['fields'] as $fi => $field): ?>
                                <?php 
                                    $fieldId = $field['id'] ?? "s{$si}_f{$fi}";
                                    $label = $t($field, 'label');
                                    $placeholder = $t($field, 'placeholder') ?? '';
                                ?>
                                <div class="form-group">
                                    <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-3">
                                        <?= e($label) ?>
                                        <?php if(($field['required'] ?? false)): ?>
                                            <span class="text-lime">*</span>
                                        <?php else: ?>
                                            <span class="text-text-secondary/50 lowercase font-normal italic ml-1">(<?= __('optional') ?>)</span>
                                        <?php endif; ?>
                                    </label>
                                    
                                <?php if (($field['type'] ?? 'textarea') === 'textarea'): ?>
                                        <textarea name="data[<?= $fieldId ?>]" rows="4" 
                                            <?= ($field['required'] ?? false) ? 'required' : '' ?>
                                            placeholder="<?= e($placeholder) ?>"
                                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-white/20 focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/50 transition-all"></textarea>
                                            
                                    <?php elseif (in_array($field['type'] ?? '', ['select', 'radio', 'checkbox'])): ?>
                                        <div class="space-y-2">
                                            <?php 
                                                $originalOptions = $field['options'] ?? [];
                                                $translatedOptions = $t($field, 'options');
                                                // Ensure translated array matches original length
                                                if (!is_array($translatedOptions) || count($translatedOptions) !== count($originalOptions)) {
                                                    $translatedOptions = $originalOptions;
                                                }
                                            ?>
                                            <?php foreach ($originalOptions as $oi => $origOpt): ?>
                                                <?php $displayOpt = $translatedOptions[$oi] ?? $origOpt; ?>
                                                <label class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/5 hover:border-lime/30 cursor-pointer transition-all">
                                                    <input type="<?= ($field['type'] ?? '') === 'select' ? 'radio' : ($field['type'] ?? 'radio') ?>" 
                                                           name="data[<?= $fieldId ?>]<?= ($field['type'] ?? '') === 'checkbox' ? '[]' : '' ?>" 
                                                           value="<?= e($origOpt) ?>"
                                                           class="text-lime bg-black/20 border-white/10 rounded focus:ring-lime/50">
                                                    <span class="text-sm text-white"><?= e($displayOpt) ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>

                                    <?php else: ?>
                                        <input type="<?= $field['type'] ?? 'text' ?>" name="data[<?= $fieldId ?>]" 
                                            <?= ($field['required'] ?? false) ? 'required' : '' ?>
                                            placeholder="<?= e($placeholder) ?>"
                                            class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-white/20 focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/50 transition-all">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Last Step: Basic Info -->
            <div class="wizard-step hidden opacity-0 translate-x-4 transition-all duration-500" data-step="<?= count($sections) ?>">
                <div class="space-y-6">
                    <div class="glass-panel p-8 border-white/5 rounded-3xl relative overflow-hidden group hover:border-lime/20 transition-all duration-500">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-lime/5 rounded-full filter blur-3xl group-hover:bg-lime/10 transition-all duration-500"></div>
                        
                        <h2 class="text-xl font-bold text-white mb-6 relative z-10"><?= __('basic_info') ?></h2>
                        
                        <div class="space-y-5 relative z-10">
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-2"><?= __('name') ?></label>
                                <input type="text" name="client_name" required 
                                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-white/20 focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/50 transition-all"
                                       placeholder="<?= __('your_name') ?? '' ?>">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-2"><?= __('email') ?></label>
                                <input type="email" name="client_email" required 
                                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-white/20 focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/50 transition-all"
                                       placeholder="<?= __('your_email') ?? '' ?>">
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase font-bold tracking-widest text-text-secondary mb-2"><?= __('whatsapp') ?></label>
                                <input type="tel" name="client_phone" required 
                                       class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-white/20 focus:outline-none focus:border-lime/50 focus:ring-1 focus:ring-lime/50 transition-all"
                                       placeholder="<?= __('your_whatsapp') ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex items-center justify-between pt-4">
                <button type="button" id="diag-prev" class="hidden px-6 py-3 text-sm font-semibold text-text-secondary hover:text-white transition-colors">
                    <?= __('back') ?>
                </button>
                <div class="flex-1"></div>
                <button type="button" id="diag-next" class="group relative px-8 py-3 bg-lime text-black font-semibold rounded-full hover:bg-lime/90 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-lime/25 transform hover:-translate-y-0.5">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <?= __('next') ?>
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </span>
                </button>
                <button type="submit" id="diag-submit" class="hidden px-12 py-4 bg-lime text-black font-bold rounded-2xl hover:bg-lime-400 transition-all shadow-[0_0_20px_rgba(163,230,53,0.3)]">
                    <?= __('finish_diagnosis') ?>
                </button>
            </div>
        </form>
    </div>
</section>

<script src="/js/diagnostico.js"></script>

<script>
// Palavras dinâmicas para o título (baseado no idioma)
const locale = '<?= App::locale() ?>';
const wordsByLocale = {
    'pt': ['Desbloqueie', 'Escale', 'Transforme', 'Otimize', 'Inove', 'Potencialize'],
    'en': ['Unlock', 'Scale', 'Transform', 'Optimize', 'Innovate', 'Amplify'],
    'es': ['Desbloquea', 'Escala', 'Transforma', 'Optimiza', 'Innova', 'Potencia']
};

const words = wordsByLocale[locale] || wordsByLocale['pt'];
let currentIndex = 0;
const titleElement = document.getElementById('dynamic-title');

if (titleElement) {
    setInterval(() => {
        // Fade out
        titleElement.style.opacity = '0';
        titleElement.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            // Change word
            currentIndex = (currentIndex + 1) % words.length;
            titleElement.textContent = words[currentIndex];
            
            // Fade in
            titleElement.style.opacity = '1';
            titleElement.style.transform = 'translateY(0)';
        }, 300);
    }, 3000);
    
    // Add transition styles
    titleElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    titleElement.style.display = 'inline-block';
    titleElement.style.color = '#C8FF00'; // nova cor
}
</script>
