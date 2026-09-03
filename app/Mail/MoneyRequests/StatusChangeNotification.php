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
 * Notifica un cambio de estado de una solicitud.
 * Se envía en cola.
 */
class StatusChangeNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly MoneyRequest $moneyRequest,
    ) {}

    public function envelope(): Envelope
    {
        $id = $this->moneyRequest->id;
        $status = $this->moneyRequest->status->label();

        return new Envelope(
            subject: "Solicitud #$id cambio a $status",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mails.requests.status_change',
            with: [
                'request' => $this->moneyRequest,
            ],
        );
    }
}
