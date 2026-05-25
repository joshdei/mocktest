<?php

namespace App\Mail;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewComplaintAdminMail extends Mailable
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
            subject: 'New Complaint Submitted - ' . $this->complaint->topic_label,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-complaint',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}