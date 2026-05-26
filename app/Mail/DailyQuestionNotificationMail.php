<?php

namespace App\Mail;

use App\Models\WeeklyQuestionSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Markdown\MarkdownMailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailyQuestionNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $userName,
        public $schedule
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎯 Today\'s Question is Live — Earn ₦10 on Psalmedu!'
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.daily_question_notification',
            with: [
                'userName' => $this->userName,
                'schedule' => $this->schedule,
            ]
        );
    }
}

