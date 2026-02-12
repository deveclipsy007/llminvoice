# Mapeamento do projeto

- Rotas publicas mapeadas em `config/routes.php`:
  - `GET /diagnostico`
  - `POST /diagnostico`
  - `GET /diagnostico/obrigado`
  - `POST /diagnostico/cadastro`
  - `POST /api/diagnostico/analyze`
- Controller principal: `src/Controllers/Client/PublicFormController.php`
- Tabela principal: `public_form_responses`

## Pontos de atencao observados

- Fluxo possui fallback local para analise quando API externa falha.
- Cadastro posterior cria usuario `client` e envia credenciais por email.
