<?php

declare(strict_types=1);

namespace App\Services;

/**
 * PdfService - Generate PDFs using Dompdf.
 */
class PdfService
{
    /**
     * Generate proposal PDF.
     */
    public static function generateProposal(int $proposalId): string
    {
        $proposal = ProposalService::getProposalWithVersions($proposalId);
        if (!$proposal) throw new \RuntimeException("Proposal not found: {$proposalId}");

        $branding = \App\Core\Database::fetch("SELECT * FROM branding LIMIT 1");
        $version = $proposal['current_version'];
        $content = json_decode($version['content'] ?? '{}', true);

        // Render PDF template
        $data = [
            'proposal' => $proposal,
            'version'  => $version,
            'content'  => $content,
            'branding' => $branding,
        ];

        extract($data);
        ob_start();
        require \App\Core\App::basePath() . '/templates/pdf/proposal.php';
        $html = ob_get_clean();

        // Generate PDF with Dompdf
        $dompdf = new \Dompdf\Dompdf([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'Helvetica',
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Save PDF to disk and return the path.
     */
    public static function saveProposalPdf(int $proposalId): string
    {
        $pdf = self::generateProposal($proposalId);
        $dir = \App\Core\App::basePath() . '/storage/proposals';

        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = "proposal_{$proposalId}_" . date('Ymd_His') . '.pdf';
        $path = "{$dir}/{$filename}";
        file_put_contents($path, $pdf);

        // Update proposal with pdf path
        \App\Core\Database::update('proposals', $proposalId, [
            'pdf_path' => "storage/proposals/{$filename}",
        ]);

        return $path;
    }
}
