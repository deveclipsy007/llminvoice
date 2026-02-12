<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Core\Database;

/**
 * EmailService - Send emails via SMTP using PHPMailer.
 */
class EmailService
{
    /**
     * Send proposal link email.
     */
    public static function sendProposalLink(int $proposalId, string $shareUrl): bool
    {
        $proposal = ProposalService::getProposalWithVersions($proposalId);
        if (!$proposal) return false;

        $branding = Database::fetch("SELECT * FROM branding LIMIT 1");
        $version = $proposal['current_version'];

        // Render email template
        $data = [
            'proposal'  => $proposal,
            'version'   => $version,
            'shareUrl'  => $shareUrl,
            'branding'  => $branding,
        ];

        extract($data);
        ob_start();
        require \App\Core\App::basePath() . '/templates/emails/proposal-link.php';
        $bodyHtml = ob_get_clean();

        $subject = __('proposal_email_subject', [
            'company' => $branding['company_name'] ?? 'LLMInvoice',
        ]);

        // Log the email
        $emailLogId = Database::insert('email_logs', [
            'proposal_id' => $proposalId,
            'to_email'    => $proposal['contact_email'],
            'to_name'     => $proposal['contact_name'],
            'subject'     => $subject,
            'body_html'   => $bodyHtml,
            'status'      => 'sending',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        try {
            $result = self::send(
                $proposal['contact_email'],
                $proposal['contact_name'],
                $subject,
                $bodyHtml
            );

            Database::update('email_logs', $emailLogId, [
                'status'  => $result ? 'sent' : 'failed',
                'sent_at' => $result ? date('Y-m-d H:i:s') : null,
            ]);

            return $result;
        } catch (\Throwable $e) {
            Database::update('email_logs', $emailLogId, [
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            \App\Core\Logger::error("Email send failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send a follow-up email with AI-generated content.
     */
    public static function sendFollowUp(int $clientId, string $subject, string $bodyHtml): bool
    {
        $client = Database::fetch("SELECT * FROM clients WHERE id = ?", [$clientId]);
        if (!$client || !$client['contact_email']) return false;

        $emailLogId = Database::insert('email_logs', [
            'to_email'   => $client['contact_email'],
            'to_name'    => $client['contact_name'],
            'subject'    => $subject,
            'body_html'  => $bodyHtml,
            'status'     => 'sending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        try {
            $result = self::send($client['contact_email'], $client['contact_name'], $subject, $bodyHtml);
            Database::update('email_logs', $emailLogId, [
                'status' => $result ? 'sent' : 'failed',
                'sent_at' => $result ? date('Y-m-d H:i:s') : null,
            ]);
            return $result;
        } catch (\Throwable $e) {
            Database::update('email_logs', $emailLogId, [
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Low-level send via PHPMailer.
     */
    private static function send(string $toEmail, string $toName, string $subject, string $bodyHtml): bool
    {
        $config = config('email');
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = $config['smtp_host'] ?? env('MAIL_HOST', 'smtp.gmail.com');
        $mail->SMTPAuth   = true;
        $mail->Username   = $config['smtp_user'] ?? env('MAIL_USERNAME', '');
        $mail->Password   = $config['smtp_pass'] ?? env('MAIL_PASSWORD', '');
        $mail->SMTPSecure = $config['smtp_encryption'] ?? env('MAIL_ENCRYPTION', 'tls');
        $mail->Port       = (int) ($config['smtp_port'] ?? env('MAIL_PORT', 587));
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(
            $config['from_email'] ?? env('MAIL_FROM_ADDRESS', 'noreply@llminvoice.com'),
            $config['from_name'] ?? env('MAIL_FROM_NAME', 'LLMInvoice')
        );

        $mail->addAddress($toEmail, $toName);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;
        $mail->AltBody = strip_tags($bodyHtml);

        return $mail->send();
    }
}
