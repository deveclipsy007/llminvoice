<?php

// update_briefing_template.php
// Refactors the default 80/20 template to prioritize clicks and make writing optional.

$dbPath = 'c:\Users\Yohann\Downloads\Organizados\llmvoice2.0\database\database.sqlite';
echo "Searching for database at: $dbPath\n";

if (!file_exists($dbPath)) {
    echo "Files in " . dirname($dbPath) . ":\n";
    print_r(scandir(dirname($dbPath)));
    die("Error: database.sqlite not found at $dbPath\n");
}

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Get current template
    $stmt = $db->prepare("SELECT id, structure FROM form_templates WHERE name = 'Briefing 80/20' LIMIT 1");
    $stmt->execute();
    $template = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$template) {
        die("Error: 'Briefing 80/20' template not found.\n");
    }

    $structure = json_decode($template['structure'], true);
    if (!$structure) {
        die("Error decoding JSON structure.\n");
    }

    // 2. Refactor logic
    foreach ($structure['sections'] as &$section) {
        foreach ($section['fields'] as &$field) {
            // Rule 1: All writing fields (text/textarea) are optional
            if ($field['type'] === 'textarea' || $field['type'] === 'text') {
                $field['required'] = false;
            }
        }
    }

    // 3. Add specific clickable options based on form_perfect.md scenarios
    
    $newSections = [];
    foreach ($structure['sections'] as $section) {
        $newFields = [];
        foreach ($section['fields'] as $field) {
            
            if ($field['id'] === 'q1') {
                $newFields[] = [
                    'id' => 'q1_select',
                    'type' => 'select',
                    'label' => 'Qual o objetivo principal desta iniciativa?',
                    'label_en' => 'What is the primary objective of this initiative?',
                    'label_es' => '¿Cuál es el objetivo principal de esta iniciativa?',
                    'options' => ['Aumentar Vendas / Receita', 'Reduzir Custos / Retrabalho', 'Melhorar Atendimento / SLA', 'Decidir com Dados / BI', 'Escalar Operação', 'Compliance / Segurança'],
                    'required' => true,
                    'ai_weight' => 'high'
                ];
                $field['label'] = 'Detalhes adicionais sobre o objetivo (opcional):';
                $field['label_en'] = 'Additional details about the objective (optional):';
                $field['label_es'] = 'Detalles adicionales sobre el objetivo (opcional):';
            }

            if ($field['id'] === 'q2') {
                $newFields[] = [
                    'id' => 'q2_check',
                    'type' => 'checkbox',
                    'label' => 'O que mais te impede de chegar lá hoje?',
                    'label_en' => 'What prevents you the most from getting there today?',
                    'label_es' => '¿Qué es lo que más te impide llegar allí hoy?',
                    'options' => ['Processos Manuais / Planilhas', 'Dados Espalhados / Sem Confiança', 'Falta de Integração entre Sistemas', 'Equipe Sobrecarregada', 'Processo não Padronizado', 'Tecnologia Obsoleta'],
                    'required' => true,
                    'ai_weight' => 'high'
                ];
                $field['label'] = 'Descreva a "dor" principal (opcional):';
                $field['label_en'] = 'Describe the main "pain" (optional):';
                $field['label_es'] = 'Descritiba el "dolor" principal (opcional):';
            }

            if ($field['id'] === 'q4') {
                $field['type'] = 'checkbox';
                $field['label'] = 'Onde você mais perde tempo com tarefas manuais atualmente?';
                $field['label_en'] = 'Where do you lose most time with manual tasks currently?';
                $field['label_es'] = '¿Dónde pierdes más tiempo con tarefas manuales atualmente?';
                $field['options'] = ['Copiar/Colar entre sistemas', 'Preencher Planilhas', 'Caçar informações em vários lugares', 'Gerar Relatórios', 'Follow-up de leads', 'Aprovar documentos/pedidos'];
                $field['required'] = true;
            }

            if ($field['id'] === 'q9') {
                $field['type'] = 'select';
                $field['label'] = 'Existe uma "Fonte Única da Verdade" para seus dados (Clientes/Produtos)?';
                $field['label_en'] = 'Is there a "Single Source of Truth" for your data?';
                $field['label_es'] = '¿Existe una "Fuente Única de Verdad" para sus datos?';
                $field['options'] = ['Sim, um sistema central oficial', 'Não, cada área tem o seu', 'Parcialmente controlado', 'Tudo em Planilhas', 'Não sei'];
                $field['required'] = true;
            }

            if ($field['id'] === 'q24') {
                $field['type'] = 'select';
                $field['label'] = 'Quem é o tomador de decisão final?';
                $field['label_en'] = 'Who is the final decision maker?';
                $field['label_es'] = '¿Quién es el tomador de decisiones final?';
                $field['options'] = ['Eu mesmo', 'Diretoria / CEO', 'Conselho', 'TI / Compras', 'Comitê de várias áreas'];
                $field['required'] = true;
            }

            $newFields[] = $field;
        }
        $section['fields'] = $newFields;
        $newSections[] = $section;
    }

    $structure['sections'] = $newSections;

    // 4. Update database
    $updatedJson = json_encode($structure, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $updateStmt = $db->prepare("UPDATE form_templates SET structure = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
    $updateStmt->execute([$updatedJson, $template['id']]);

    echo "Successfully refactored 'Briefing 80/20' template.\n";

} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
