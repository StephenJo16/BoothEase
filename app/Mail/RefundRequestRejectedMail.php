<?php

namespace App\Mail;

use App\Models\RefundRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundRequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $refundRequest;
    public $booking;
    public $event;

    /**
     * Create a new message instance.
     */
    public function __construct(RefundRequest $refundRequest)
    {
        $this->refundRequest = $refundRequest;
        $this->booking = $refundRequest->booking;
        $this->event = $refundRequest->booking->booth->event;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Refund Request Rejected - ' . $this->event->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.refund-request-rejected',
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
