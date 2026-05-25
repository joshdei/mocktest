<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint;

    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We Received Your Message - ' . $this->complaint->topic_label,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}