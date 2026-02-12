# MANIFESTO - proposal-versioning

## Proposito

Preservar historico de negociacao e garantir rastreabilidade das alteracoes.

## Limites

- Nao altera proposta aceita automaticamente.
- Nao remove versoes antigas sem processo explicito.

## Riscos

- Inconsistencia entre `current_version_id` e versoes existentes.
- Perda de contexto comercial em alteracoes sem `change_summary`.
