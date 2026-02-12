---
name: admin-reports-ui
description: Harmoniza a tela de relatorios do admin ao estilo visual aprovado da area cliente.
bundle: frontend
risk: caution
version: 1.0.0
---

# admin-reports-ui

Skill para evoluir UX/UI de relatorios sem alterar logica de dados.

## Inputs

- `report_types` (`conversion`, `revenue`, `ai`, `email`)
- `filter_fields` (`type`, `date_from`, `date_to`)
- `visual_tokens` (paleta, bordas, profundidade)

## Outputs

- `templates/pages/admin/reports.php`

## Criterios de aceite

- Filtros e exportacao continuam funcionais.
- Cards/tabelas seguem estilo green-glass da area cliente.
- Layout permanece responsivo em mobile e desktop.
