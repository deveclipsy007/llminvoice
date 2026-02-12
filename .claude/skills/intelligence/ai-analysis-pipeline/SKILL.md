---
name: ai-analysis-pipeline
description: Estrutura e valida pipeline de analise IA para clientes e propostas.
bundle: intelligence
risk: caution
version: 1.0.0
---

# ai-analysis-pipeline

Skill para evoluir fluxo de analise IA mantendo previsibilidade, custo controlado e fallback.

## Inputs

- `provider`: `claude`, `openai` ou `groq`
- `model_policy`: limites de tokens, temperatura e timeout
- `output_contract`: chaves JSON obrigatorias de analise
- `failure_policy`: estrategia de retry/fallback

## Outputs

- `config/ai.php`
- `src/Services/AiService.php`
- Atualizacoes opcionais em parser/prompt builder

## Fontes do projeto

- `config/ai.php`
- `src/Services/AiService.php`
- `src/Services/AiPromptBuilder.php`
- `src/Services/AiResultParser.php`

## Passo a passo

1. Definir contrato de saida JSON (campos obrigatorios).
2. Ajustar chamada por provider com timeout e tratamento de erro.
3. Manter telemetria minima (`tokens`, `cost`, `processing_time_ms`).
4. Garantir fallback para erro de provider.
5. Validar compatibilidade com status do cliente (`pending/processing/completed/failed`).

## Criterios de aceite

- Erro de API nao quebra fluxo inteiro.
- Saida parseada segue contrato minimo esperado.
- Status da analise e do cliente mantem coerencia de estado.

## Nao faz

- Nao adiciona novo provider sem chave de API configurada.
- Nao envia dados sensiveis para logs sem mascaramento.
