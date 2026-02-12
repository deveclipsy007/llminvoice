# MANIFESTO - auth-rbac-hardening

## Proposito

Reduzir superficie de acesso indevido e padronizar decisao de autorizacao.

## Limites

- Nao substitui auditoria formal de seguranca.
- Nao cobre criptografia em repouso/transito por si so.

## Riscos

- Mudancas de RBAC podem bloquear operacoes legitimas.
- Falhas de configuracao podem expor endpoints sensiveis.

## Responsabilidade

- Time de engenharia aplica e testa.
- Dono do produto aprova politicas de acesso.
- Alteracoes em rotas criticas exigem revisao dedicada.
