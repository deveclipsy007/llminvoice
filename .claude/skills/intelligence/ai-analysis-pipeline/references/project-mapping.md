# Mapeamento do projeto

- Config de provider/modelo em `config/ai.php`.
- Orquestracao de analise em `src/Services/AiService.php`.
- Prompt builder em `src/Services/AiPromptBuilder.php`.
- Parse da resposta em `src/Services/AiResultParser.php`.

## Pontos de estabilidade

- Atualizacao de status em `AiAnalysis` e `Client`.
- Persistencia de `raw_response`, `tokens`, `cost_usd`, `processing_time_ms`.
- Branch de erro com `markFailed` e log estruturado.
