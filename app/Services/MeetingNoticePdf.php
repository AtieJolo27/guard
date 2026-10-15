<?php

namespace App\Services;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

class MeetingNoticePdf
{
    public function data(object $report): array
    {
        return [
            'report' => $report,
            'studentEmail' => trim($report->sr_code).'@g.batstate-u.edu.ph',
            'reference' => 'SV-'.Carbon::parse($report->created_at)->format('Y').'-'.str_pad($report->id, 4, '0', STR_PAD_LEFT),
            'meeting' => Carbon::parse($report->meeting_at, 'Asia/Singapore'),
            'incident' => Carbon::parse($report->incident_at, 'Asia/Singapore'),
            'issued' => now()->timezone('Asia/Singapore')->format('F j, Y'),
            'templateHeader' => 'data:image/png;base64,'.base64_encode(file_get_contents(resource_path('notice-assets/template-header.png'))),
            'templateFooter' => 'data:image/png;base64,'.base64_encode(file_get_contents(resource_path('notice-assets/template-footer.png'))),
            'logo' => 'data:image/png;base64,'.base64_encode(file_get_contents(resource_path('notice-assets/letterhead.png'))),
        ];
    }

    public function generate(object $report): string
    {
        $options = new Options(['isRemoteEnabled' => false, 'isPhpEnabled' => false, 'isJavascriptEnabled' => false, 'defaultFont' => 'DejaVu Sans']);
        $options->setChroot(resource_path('notice-assets'));
        $pdf = new Dompdf($options);
        $pdf->setPaper([0, 0, 612, 936]); // Template: 8.5 x 13 inches.
        $pdf->loadHtml(view('pdf.meeting-notice', $this->data($report))->render());
        $pdf->render();

        return $pdf->output();
    }
}
