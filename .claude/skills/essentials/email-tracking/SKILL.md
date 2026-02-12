---
name: email-tracking
description: Estrutura rastreamento de abertura e clique em emails comerciais.
bundle: essentials
risk: caution
version: 1.0.0
---

# email-tracking

Skill para manter consistencia entre envio de email e eventos de tracking.

## Inputs

- `tracking_strategy` (pixel/open + click redirect)
- `status_mapping`
- `privacy_policy`

## Outputs

- `src/Controllers/Admin/EmailController.php`
- `src/Services/EmailService.php`
- `config/routes.php`

## Criterios de aceite

- Rotas `/track/open/{trackingId}` e `/track/click/{trackingId}` funcionam.
- Evento de open/click atualiza status e timestamp.
- Click redireciona para URL final valida.
