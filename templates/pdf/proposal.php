<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11pt; color: #333; line-height: 1.5; }
        .header { background: #050505; color: #FFF; padding: 30px 40px; }
        .header h1 { font-size: 22pt; margin-bottom: 5px; }
        .header .subtitle { color: #C8FF00; font-size: 10pt; }
        .client-info { padding: 20px 40px; border-bottom: 1px solid #E5E7EB; }
        .client-info p { font-size: 10pt; color: #666; }
        .content { padding: 30px 40px; }
        .phase { margin-bottom: 20px; padding: 15px; border: 1px solid #E5E7EB; border-radius: 8px; }
        .phase-title { font-size: 13pt; font-weight: bold; color: #111; margin-bottom: 8px; }
        .phase-desc { font-size: 10pt; color: #666; margin-bottom: 10px; }
        .phase-meta { display: flex; justify-content: space-between; font-size: 10pt; }
        .phase-meta .duration { color: #666; }
        .phase-meta .value { color: #16A34A; font-weight: bold; }
        .services { margin-top: 8px; }
        .service-tag { display: inline-block; padding: 2px 8px; background: #F0FDF4; color: #16A34A; font-size: 9pt; border-radius: 4px; margin: 2px; }
        .total-section { margin-top: 30px; padding: 20px; background: #050505; color: #FFF; border-radius: 8px; text-align: right; }
        .total-section .label { font-size: 10pt; color: #999; }
        .total-section .amount { font-size: 20pt; font-weight: bold; color: #C8FF00; }
        .footer { padding: 30px 40px; text-align: center; font-size: 8pt; color: #999; border-top: 1px solid #E5E7EB; margin-top: 30px; }
        .conditions { padding: 15px 40px; font-size: 9pt; color: #666; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1><?= e($branding['company_name'] ?? 'LLMInvoice') ?></h1>
        <p class="subtitle"><?= __('proposal_view') ?> — v<?= $version['version_number'] ?? 1 ?></p>
    </div>

    <!-- Client Info -->
    <div class="client-info">
        <p><strong><?= __('client') ?>:</strong> <?= e($proposal['contact_name'] ?? '') ?></p>
        <p><strong><?= __('company') ?>:</strong> <?= e($proposal['company_name'] ?? '-') ?></p>
        <p><strong><?= __('date') ?>:</strong> <?= date('d/m/Y') ?></p>
        <p><strong><?= __('validity') ?>:</strong> <?= $version['validity_days'] ?? 30 ?> <?= __('days') ?></p>
    </div>

    <!-- Phases -->
    <div class="content">
        <?php
        $phases = json_decode($version['content'] ?? '{}', true)['phases'] ?? [];
        foreach ($phases as $i => $phase):
        ?>
            <div class="phase">
                <div class="phase-title"><?= __('phase') ?> <?= $i + 1 ?>: <?= e($phase['title'] ?? '') ?></div>
                <div class="phase-desc"><?= e($phase['description'] ?? '') ?></div>
                <?php if (!empty($phase['services'])): ?>
                    <div class="services">
                        <?php foreach ($phase['services'] as $svc): ?>
                            <span class="service-tag"><?= e($svc) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="phase-meta">
                    <span class="duration"><?= $phase['duration_days'] ?? '?' ?> <?= __('days') ?></span>
                    <span class="value">R$ <?= number_format((float)($phase['value'] ?? 0), 0, ',', '.') ?></span>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Total -->
        <div class="total-section">
            <p class="label"><?= __('total_value') ?></p>
            <p class="amount">R$ <?= number_format((float)($version['total_value'] ?? 0), 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- Payment Conditions -->
    <?php if ($version['payment_conditions'] ?? null): ?>
        <div class="conditions">
            <strong><?= __('payment_conditions') ?>:</strong> <?= e($version['payment_conditions']) ?>
        </div>
    <?php endif; ?>

    <!-- Footer -->
    <div class="footer">
        <p><?= e($branding['company_name'] ?? 'LLMInvoice') ?> — <?= __('copyright', ['year' => date('Y')]) ?></p>
    </div>
</body>
</html>
