<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookReport extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * O assunto do e-mail.
     *
     * @var string
     */
    public $subject;

    /**
     * Os dados do relatório.
     *
     * @var array
     */
    public $reportData;

    /**
     * Create a new message instance.
     *
     * @param string $subject
     * @param array $reportData
     */
    public function __construct(string $subject, array $reportData)
    {
        $this->subject = $subject;
        $this->reportData = $reportData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.book_report',
            with: [
                'reportData' => $this->reportData,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

