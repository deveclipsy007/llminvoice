# Mapeamento do projeto

- Login/logout/reset em `config/routes.php` com controladores de `Auth`.
- Area admin protegida com `auth` e `role:admin|user`.
- Endpoints sensiveis usam `csrf` em operacoes de escrita.

## Artefatos chave

- `src/Middleware/AuthMiddleware.php`
- `src/Middleware/RoleMiddleware.php`
- `config/permissions.php`

## Sinais de risco para revisar

- Rota de escrita sem `csrf`.
- Rota interna sem `auth`.
- Permissao muito ampla no role `user`.
