<?php

namespace App\Mail\MoneyRequests;

use App\Models\MoneyRequests\MoneyRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifica la creación de una nueva solicitud.
 * Se envía en cola.
 */
class NewRequestNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly MoneyRequest $moneyRequest,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hay una nueva solicitud',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mails.requests.new_request',
            with: [
                'request' => $this->moneyRequest,
            ],
        );
    }
}
