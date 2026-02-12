---
name: proposal-versioning
description: Padroniza criacao, aprovacao e consulta de versoes de proposta.
bundle: essentials
risk: caution
version: 1.0.0
---

# proposal-versioning

Skill para evoluir o fluxo de versao sem quebrar historico comercial.

## Inputs

- `proposal_id`
- `new_version_payload`
- `approval_policy`

## Outputs

- `src/Controllers/Admin/ProposalController.php`
- `src/Models/ProposalVersion.php`
- `src/Services/ProposalService.php`

## Fontes do projeto

- `config/routes.php` (`/api/proposals/{id}/versions`)
- `database/schema.sql` (tabelas `proposals` e `proposal_versions`)

## Criterios de aceite

- Nova versao incrementa `version_number` sem sobrescrever anterior.
- `current_version_id` referencia versao valida.
- API de versoes retorna historico ordenado.
