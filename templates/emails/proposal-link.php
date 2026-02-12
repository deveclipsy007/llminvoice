<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background: #F9FAFB; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background: #FFFFFF; }
        .header { background: #050505; padding: 30px 40px; text-align: center; }
        .header h1 { color: #FFFFFF; font-size: 24px; margin: 0; }
        .header .accent { color: #C8FF00; }
        .content { padding: 30px 40px; }
        .content h2 { color: #111; font-size: 18px; margin-bottom: 15px; }
        .content p { color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 10px; }
        .cta-button { display: inline-block; padding: 14px 32px; background: #C8FF00; color: #050505; text-decoration: none; font-weight: bold; font-size: 14px; border-radius: 12px; margin: 20px 0; }
        .value-box { background: #F0FDF4; border: 1px solid #BBF7D0; padding: 15px; border-radius: 8px; text-align: center; margin: 20px 0; }
        .value-box .label { font-size: 12px; color: #666; }
        .value-box .amount { font-size: 24px; font-weight: bold; color: #16A34A; }
        .footer { padding: 20px 40px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #E5E7EB; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1><?= e($branding['company_name'] ?? 'LLM') ?><span class="accent">Invoice</span></h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2><?= __('proposal_email_greeting', ['name' => e($proposal['contact_name'] ?? '')]) ?></h2>
            <p><?= __('proposal_email_intro') ?></p>

            <div class="value-box">
                <p class="label"><?= __('total_value') ?></p>
                <p class="amount">R$ <?= number_format((float)($version['total_value'] ?? 0), 0, ',', '.') ?></p>
            </div>

            <p style="text-align: center;">
                <a href="<?= $shareUrl ?>" class="cta-button"><?= __('proposal_email_cta') ?></a>
            </p>

            <p style="font-size: 12px; color: #999;"><?= __('proposal_email_validity', ['days' => $version['validity_days'] ?? 30]) ?></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><?= e($branding['company_name'] ?? 'LLMInvoice') ?></p>
            <p><?= __('copyright', ['year' => date('Y')]) ?></p>
        </div>
    </div>
</body>
</html>
