---
name: project-bootstrap
description: Orquestra a replicacao do LLMInvoice via bundles/skills em ordem deterministica.
bundle: bootstrap
risk: caution
version: 1.0.0
---

# project-bootstrap

Skill mestre para instalar e validar o projeto em um ambiente novo.

## Inputs

- `target_environment`: `local`, `staging` ou `production`
- `selected_bundles`: lista de bundles para carregar
- `env_values`: valores obrigatorios de ambiente
- `execution_mode`: `dry_run` ou `apply`

## Outputs

- Plano de execucao ordenado por etapas
- Checklist de pre-flight e post-flight
- Relatorio de cobertura e lacunas

## Ordem de execucao recomendada

1. `essentials/db-foundation`
2. `security/auth-rbac-hardening`
3. `essentials/public-form-intake`
4. `essentials/kanban-pipeline-rules`
5. `essentials/proposal-versioning`
6. `essentials/email-tracking`
7. `intelligence/ai-analysis-pipeline`
8. `quality/bugfix-playbook`

## Pre-flight

- Validar `.env` com chaves minimas.
- Validar acessibilidade do banco.
- Confirmar scripts de migracao e schema.
- Validar cobertura de skills (`check_coverage.py`).

## Post-flight

- Rodar `skills/scripts/validate_all.py`.
- Rodar smoke tests de rotas principais.
- Registrar gaps em `skills/coverage-matrix.json`.

## Criterios de aceite

- Fluxo de setup executado sem erro critico.
- Skills selecionadas passaram na validacao.
- Riscos de replicacao registrados com plano de mitigacao.
