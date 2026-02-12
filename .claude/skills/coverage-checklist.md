# Checklist de cobertura para replicacao total

## Objetivo

Fechar lacunas para replicar o LLMInvoice ponta a ponta apenas com skills.

## Modulos ainda nao 100% cobertos

- [x] `admin-reports` (status atual: covered)
- [x] `admin-chat-ui-flow` (status atual: covered)
- [x] `frontend-design-system` (status atual: covered)
- [ ] `deployment-devops` (status atual: missing)

## Regra de conclusao

Um modulo so vira `covered` quando existir skill com:

- `SKILL.md` com inputs/outputs explicitos
- `MANIFESTO.md` com risco e limites
- `schemas/output.schema.json`
- `scripts/validate.py`
- `tests/golden/expected_output.json`
