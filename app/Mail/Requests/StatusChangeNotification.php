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
 * Notifica un cambio de estado de una solicitud.
 * Se envía en cola.
 */
class StatusChangeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly RequestModel $requestModel,
    ) {}

    public function envelope(): Envelope
    {
        $id = $this->requestModel->id;
        $status = $this->requestModel->status->label();

        return new Envelope(
            subject: "Solicitud #$id cambio a $status",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mails.requests.status_change',
            with: [
                'request' => $this->requestModel,
            ],
        );
    }
}
