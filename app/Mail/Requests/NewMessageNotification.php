<?php

namespace App\Mail\Requests;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifica un nuevo mensaje dentro de una solicitud.
 * Se envía en cola.
 */
class NewMessageNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Message $messageModel,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hay un nuevo mensaje',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mails.requests.new_message',
            with: [
                'messageModel' => $this->messageModel,
            ],
        );
    }
}
