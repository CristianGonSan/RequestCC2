<?php

namespace App\Mail\Requests;

use App\Models\RequestModel;
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
        public readonly RequestModel $requestModel,
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
                'request' => $this->requestModel,
            ],
        );
    }
}
