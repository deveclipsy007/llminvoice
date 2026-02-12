---
name: kanban-pipeline-rules
description: Define e valida regras de transicao do pipeline kanban.
bundle: essentials
risk: caution
version: 1.0.0
---

# kanban-pipeline-rules

Skill para garantir transicoes validas entre colunas do pipeline comercial.

## Inputs

- `from_column`
- `to_column`
- `rule_type`
- `rule_config`

## Outputs

- `src/Services/PipelineService.php`
- `src/Controllers/Admin/KanbanController.php`
- `database/schema.sql` ou migration incremental

## Criterios de aceite

- Transicao invalida retorna erro localizado.
- Reordenacao de cards preserva `position_in_column`.
- Regras suportam mensagens PT/EN/ES.
