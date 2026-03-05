<?php

namespace App\Mail;

use App\Models\Configuration;
use App\Models\RequestModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StatusChangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public RequestModel $requestModel;

    /**
     * Create a new message instance.
     */
    public function __construct(RequestModel $requestModel)
    {
        $this->requestModel = $requestModel;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $id = $this->requestModel->id;
        $status = $this->requestModel->getStatusText();
        return new Envelope(
            subject: "Solicitud #$id cambio a $status",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        Log::error('CONTENT');
        return new Content(
            view: 'mails.status_change',
            with: [
                'request' => $this->requestModel,
                'statusOptions' => RequestModel::STATUSES_TEXT
                ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
