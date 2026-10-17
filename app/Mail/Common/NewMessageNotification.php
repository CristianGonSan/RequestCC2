<?php

namespace App\Mail\Common;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Notifica un nuevo mensaje asociado a un modelo.
 * Se envía en cola.
 */
class NewMessageNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $message,
        public readonly string $sender,
        public readonly string $messageDate,
        public readonly string $messageableUrl,
        public readonly string $messageableLabel,
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
            markdown: 'mails.common.new_message',
            with: [
                'message'          => $this->message,
                'sender'           => $this->sender,
                'messageDate'      => $this->messageDate,
                'messageableUrl'   => $this->messageableUrl,
                'messageableLabel' => $this->messageableLabel,
            ],
        );
    }
}
