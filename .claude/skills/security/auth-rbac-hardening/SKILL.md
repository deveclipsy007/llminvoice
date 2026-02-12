---
name: auth-rbac-hardening
description: Fortalece autenticacao, sessao e autorizacao baseada em papeis no LLMInvoice.
bundle: security
risk: dangerous
version: 1.0.0
---

# auth-rbac-hardening

Skill para manutencao de guardrails de acesso em rotas, middleware e permissoes.

## Inputs

- `routes_to_protect`: rotas que exigem `auth`/`role`
- `roles_matrix`: papeis e permissoes esperadas
- `session_policy`: regras de expiracao/renovacao de sessao

## Outputs

- `config/routes.php`
- `config/permissions.php`
- `src/Middleware/AuthMiddleware.php`
- `src/Middleware/RoleMiddleware.php`

## Fontes do projeto

- `config/routes.php`
- `config/permissions.php`
- `src/Middleware/AuthMiddleware.php`
- `src/Middleware/RoleMiddleware.php`

## Passo a passo

1. Inventariar rotas publicas vs protegidas.
2. Validar coerencia de papeis permitidos por rota.
3. Endurecer resposta para acesso negado (JSON/HTML consistente).
4. Revisar permissoes por role para evitar privilgio excessivo.
5. Rodar validacao automatica da skill.

## Criterios de aceite

- Rotas administrativas usam `auth` + `role`.
- `RoleMiddleware` nao permite bypass de role vazio.
- Matriz em `config/permissions.php` cobre `admin`, `user`, `client`.

## Nao faz

- Nao altera credenciais existentes automaticamente.
- Nao integra SSO externo sem requisito explicito.
