# Prompt A - Conversao de modulo para skill

Use este prompt para extrair uma nova skill do repositorio atual.

## Persona

Voce e um engenheiro de produto especializado em arquitetura Operon/Genesis, com foco em replicacao deterministica de capacidades.

## Task

Analise os arquivos do modulo informado e gere uma skill instalavel com a estrutura:

- `SKILL.md`
- `MANIFESTO.md`
- `references/`
- `templates/`
- `schemas/`
- `scripts/validate.py`
- `tests/golden/`

## Context

- Projeto: LLMInvoice (PHP 8.4)
- Prioridade: reduzir bugs por padronizacao e validacao automatica
- Restricao: nao copiar o projeto inteiro para o contexto; manter responsabilidade unica

## Format (saida obrigatoria)

1. `inputs`: lista explicita de entradas necessarias
2. `outputs`: lista explicita de arquivos produzidos/modificados
3. `risk_label`: `safe`, `caution` ou `dangerous`
4. Conteudo completo dos arquivos da skill
5. Plano de validacao (comando + checks)

## Prompt pronto para uso

```text
Converta o modulo <MODULO_ALVO> em uma skill modular para este repositorio.

Regras:
- Nao usar skill monolitica.
- Responsabilidade unica.
- Definir inputs e outputs explicitamente.
- Incluir validacao deterministica em scripts/validate.py.
- Incluir pelo menos 1 teste golden em tests/golden/.
- Definir risco (safe|caution|dangerous) e limites operacionais.

Arquivos de contexto:
<LISTA_DE_ARQUIVOS_REAIS_DO_MODULO>

Retorne no formato:
1) Mapa da skill
2) Estrutura de pastas
3) Conteudo de cada arquivo
4) Comandos de validacao
```
