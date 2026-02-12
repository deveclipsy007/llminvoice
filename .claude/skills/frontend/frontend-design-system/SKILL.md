---
name: frontend-design-system
description: Padroniza o visual do admin para seguir o estilo premium da area do cliente.
bundle: frontend
risk: caution
version: 1.0.0
---

# frontend-design-system

Skill para garantir paridade visual entre interfaces de usuario e admin.

## Inputs

- `source_style`: arquivos de referencia do estilo cliente
- `target_scope`: telas admin a serem harmonizadas
- `component_tokens`: variaveis de cor, tipografia, espacamento e estados
- `mobile_requirements`: regras de responsividade

## Outputs

- `templates/layouts/admin.php`
- `templates/partials/sidebar.php`
- `templates/partials/topbar.php`
- Ajustes opcionais em `public/assets/css/app.css`

## Regras de design

1. O admin deve usar a mesma familia visual da area cliente (green glass, gradient depth, contraste suave).
2. Evitar tons cinza dominantes e blocos sem identidade.
3. Garantir legibilidade em desktop e mobile.
4. Preservar usabilidade de formularios, tabelas e filtros.

## Criterios de aceite

- Header, sidebar e cards do admin refletem o estilo cliente.
- Estados de hover/focus seguem token unico de acento.
- Nenhuma quebra de navegacao mobile.
