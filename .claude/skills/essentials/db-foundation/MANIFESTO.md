# MANIFESTO - db-foundation

## Proposito

Padronizar evolucao de schema para reduzir regressao e drift de banco.

## Limites

- Esta skill nao aprova alteracoes destrutivas por conta propria.
- Esta skill nao cria carga massiva de dados.

## Riscos

- Quebra de compatibilidade com queries existentes.
- Locks em tabela durante alteracao de estrutura.

## Responsabilidade

- Autor da mudanca define SQL e rollback.
- Revisor valida impacto funcional e operacional.
- Execucao em ambiente critico requer aprovacao humana.
