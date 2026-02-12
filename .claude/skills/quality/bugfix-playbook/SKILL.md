---
name: bugfix-playbook
description: Playbook para corrigir bugs recorrentes com reproducao, fix minimo e teste de regressao.
bundle: quality
risk: safe
version: 1.0.0
---

# bugfix-playbook

Skill para reduzir retrabalho e regressao em correcoes de bug.

## Inputs

- `bug_report`
- `repro_steps`
- `affected_files`
- `expected_behavior`

## Outputs

- Patch de codigo minimo
- Caso de regressao em `testsprite_tests/` ou suite equivalente
- Nota de causa raiz

## Passo a passo

1. Reproduzir bug com passos objetivos.
2. Identificar causa raiz (nao apenas sintoma).
3. Implementar fix minimo e seguro.
4. Adicionar teste de regressao.
5. Validar que bug nao reapareceu.

## Criterios de aceite

- Repro falha antes e passa depois do fix.
- Teste novo cobre cenario real do bug.
- Mudanca nao aumenta escopo sem necessidade.
