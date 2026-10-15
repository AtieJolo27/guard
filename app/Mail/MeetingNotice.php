<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class MeetingNotice extends Mailable
{
    public function __construct(public array $notice, public string $recipientName, public string $pdfBytes) {}
    public function envelope(): Envelope { return new Envelope(subject: 'Notice of Meeting - '.$this->notice['reference']); }
    public function content(): Content { return new Content(text: 'mail.meeting-notice'); }
    public function attachments(): array { return [Attachment::fromData(fn () => $this->pdfBytes, 'Meeting-Notice-'.$this->notice['reference'].'.pdf')->withMime('application/pdf')]; }
}
