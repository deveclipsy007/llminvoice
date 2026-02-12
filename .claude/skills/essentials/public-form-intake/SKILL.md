---
name: public-form-intake
description: Implementa ou corrige fluxo de formulario publico com analise inicial e persistencia segura.
bundle: essentials
risk: caution
version: 1.0.0
---

# public-form-intake

Skill para manutencao do fluxo `/diagnostico` e endpoints relacionados.

## Inputs

- `form_fields`: campos esperados no payload
- `validation_rules`: regras obrigatorias
- `analysis_mode`: `ai` ou `fallback`
- `notification_channels`: ex: `telegram`, `email`

## Outputs

- Atualizacao em `config/routes.php` (se necessario)
- Atualizacao em `src/Controllers/Client/PublicFormController.php`
- Atualizacao opcional de migration de suporte

## Fontes do projeto

- `config/routes.php`
- `src/Controllers/Client/PublicFormController.php`
- `database/migrations/008_public_form_responses.sql`

## Passo a passo

1. Validar entradas obrigatorias e termos de uso.
2. Implementar interface de wizard multi-step em `templates/pages/public/diagnostico.php`.
    - **Resiliência**: Utilizar o operador null-safe (`??`) para acessar chaves do JSON estrutural (ex: `$field['type'] ?? 'text'`). Isso evita que um erro PHP interrompa o layout e cause problemas de encoding.
    - **Arquitetura de Tradução (Ideal)**:
        - Criar helper `$t($obj, $key)` que suporte strings e arrays (para `options`).
        - **Display vs Value**: Traduzir apenas o rótulo visual (`display`). O `value` do input deve permanecer na língua original (PT) para garantir consistência dos dados no banco e evitar quebras em relatórios/IA.
        - **Fallback**: Sempre garantir fallback para o array original se a tradução falhar ou tiver tamanho diferente.
3. Adicionar barra de progresso e mensagens motivacionais em `public/js/diagnostico.js`.
4. Persistir submissao com metadados (`ip`, `user_agent`, `submitted_at`).
5. Retornar resposta JSON e disparar redirecionamento para página de agradecimento.

## Troubleshooting: Encoding (Mojibake)

Se a página exibir caracteres como "Ã§" em vez de "ç":
1. Verifique se há erros PHP ocorrendo durante o rendering do template.
2. Certifique-se de que o layout (que contém `<meta charset="UTF-8">`) está sendo aplicado e não foi interrompido por um crash.
3. Verifique se o banco de dados e os arquivos PHP estão salvos em UTF-8 sem BOM.
4. Use `curl.exe -s URL` para verificar se o HTML bruto contém a declaração de charset antes de qualquer script.

## Criterios de aceite

- Erros de validacao retornam 422 com payload consistente.
- Fluxo nao depende 100% de API externa.
- Registro salvo com status inicial `pending`.

## Nao faz

- Nao aprova/rejeita respostas automaticamente.
- Nao cria conta de usuario sem validacao de email.
