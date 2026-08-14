<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $password = '';

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cuenta Creada',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mails.requests.new_user',
            with: ['user' => $this->user, 'password' => $this->password]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
