---
name: form-builder-management
description: Gerencia a criação, edição e exclusão de modelos de formulários no admin.
bundle: essentials
risk: caution
version: 1.0.0
---

# form-builder-management

Skill para gerenciar os modelos de formulários dinâmicos (`form_templates`).

## Inputs

- `template_name`: nome do formulário
- `structure`: JSON definindo seções e campos
- `is_active`: status de ativação
- `description`: descrição opcional

## Outputs

- Criação/Edição em `form_templates`
- Interface administrativa em `templates/pages/admin/form-editor.php`

## Fontes do projeto

- `config/routes.php`
- `src/Controllers/Admin/FormBuilderController.php`
- `templates/pages/admin/form-builder.php`
- `templates/pages/admin/form-editor.php`

## Passo a passo

1. Definir as rotas de gerenciamento no `config/routes.php`.
2. Implementar as ações `index`, `create`, `edit`, `save` e `delete` no `FormBuilderController`.
3. Criar a interface de edição visual Duo-Mode (`form-editor.php`) que permita alternar entre Builder Visual e Editor JSON com sincronização em tempo real.
4. Garantir que o link para gerenciamento esteja acessível via barra lateral ou página de respostas.
5. Sincronizar as alterações visuais com o JSON subjacente para garantir persistência correta.
6. **Validar Estrutura JSON**: Garantir que cada campo possua obrigatoriamente as chaves `id`, `label` e `type`. A ausência de `type` pode quebrar o renderizador e causar erros de encoding (mojibake).
7. **Arquitetura de Tradução (IA)**: Utilizar o `AiTranslationService` e garantir que o prompt:
    - Exija `label` para TODOS os campos no i18n (mesmo os com opções).
    - Exija que arrays de `options` traduzidos tenham o MESMO comprimento do original.
8. Utilizar o `AiTranslationService` para traduzir automaticamente o conteúdo do formulário para EN/ES.

## Criterios de aceite

- É possível criar um novo formulário do zero.
- É possível editar um formulário existente e salvar as alterações.
- A exclusão de formulários funciona e remove o registro do banco.
- O link para "Modelos de Formulários" está visível no menu lateral (ou sob Settings).

## Nao faz

- Não altera as respostas já submetidas por clientes.
- Não valida a lógica de negócio dos campos (apenas a estrutura JSON).
