---
name: db-foundation
description: Padrao para criar e validar mudancas de schema e migration no LLMInvoice.
bundle: essentials
risk: caution
version: 1.0.0
---

# db-foundation

Skill para evolucao de banco com foco em consistencia e rollback seguro.

## Inputs

- `change_name`: nome curto da mudanca (ex: add_lead_score)
- `target_tables`: tabelas impactadas
- `migration_sql`: SQL da migration
- `rollback_sql` (obrigatorio quando aplicavel)

## Outputs

- `database/migrations/<NNN>_<change_name>.sql`
- Atualizacao opcional de `database/schema.sql`
- Registro em `skills/essentials/db-foundation/tests/golden/expected_output.json` (quando atualizar a skill)

## Fontes do projeto

- `database/schema.sql`
- `database/migrations/*.sql`
- `database/run_migrations.php`

## Passo a passo

1. Identifique impacto de FK, indices e dados existentes.
2. Crie migration incremental (nao editar historico aplicado).
3. Inclua SQL idempotente quando possivel (`IF EXISTS`/`IF NOT EXISTS`).
4. Garanta alinhamento entre migration e schema consolidado.
5. Rode validacao deterministica da skill.

## Criterios de aceite

- Migration tem nome incremental no padrao do projeto.
- SQL parseavel sem placeholders vazios.
- Arquivo de schema continua valido como referencia consolidada.

## Nao faz

- Nao executa migration em producao automaticamente.
- Nao deleta dados sem aprovacao explicita.
