# Sequencia de bootstrap

## Etapa 1 - Base

- Aplicar schema/migrations com `db-foundation`.
- Validar conexao e charset.

## Etapa 2 - Seguranca

- Aplicar `auth-rbac-hardening`.
- Revisar rotas de escrita com `auth + role + csrf`.

## Etapa 3 - Fluxos comerciais

- Aplicar intake publico, kanban, versionamento e tracking.

## Etapa 4 - Inteligencia

- Configurar provider IA e validar fallback.

## Etapa 5 - Qualidade

- Aplicar `bugfix-playbook` para padrao de correcoes futuras.
