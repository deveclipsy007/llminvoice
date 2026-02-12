# Skills do projeto LLMInvoice

Este diretorio transforma partes do projeto em habilidades modulares, testaveis e replicaveis.

## Estrategia usada

- Fatiamento por responsabilidade unica (sem skill monolitica).
- Organizacao por bundles para carregar somente o necessario.
- Estrutura canonica por skill: `SKILL.md`, `MANIFESTO.md`, `references/`, `templates/`, `schemas/`, `scripts/`, `tests/`.
- Validacao deterministica local com `scripts/validate.py` em cada skill.
- Teste golden por skill em `tests/golden/`.

## Bundles atuais

- `essentials`: base de dados e intake publico.
- `security`: autenticacao e RBAC.
- `intelligence`: pipeline de analise com IA.
- `quality`: playbooks de correcao e reducao de regressao.
- `frontend`: consistencia visual entre cliente e admin.
- `bootstrap`: orquestracao de replicacao completa do projeto.

## Skills adicionadas nesta fase

- `proposal-versioning`
- `email-tracking`
- `kanban-pipeline-rules`
- `bugfix-playbook`
- `frontend-design-system`
- `admin-reports-ui`
- `admin-chat-ui-flow`
- `project-bootstrap`
- `form-builder-management`

## Como usar

1. Escolha a skill em `skills/index.json`.
2. Leia `SKILL.md` e confirme entradas/saidas.
3. Copie/adapte templates da pasta `templates/`.
4. Rode validacao local da skill:

```bash
python skills/<bundle>/<skill>/scripts/validate.py
```

5. Rode validacao geral:

```bash
python skills/scripts/validate_all.py
```

6. Em CI (GitHub Actions), a validacao roda automaticamente a cada push/PR.

## Replicacao ponta a ponta

- Use a skill `project-bootstrap` para executar a ordem de instalacao dos modulos.
- Confira cobertura em `skills/coverage-matrix.json`.
- Rode `python skills/scripts/check_coverage.py` para validar lacunas antes de replicar em novo ambiente.

## Convencoes de risco

- `safe`: alteracoes locais e reversiveis.
- `caution`: pode impactar regras de negocio ou dados.
- `dangerous`: pode impactar infraestrutura, dados criticos ou seguranca.
