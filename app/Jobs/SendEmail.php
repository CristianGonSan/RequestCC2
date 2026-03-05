<?php

namespace App\Jobs;

use App\Mail\NewMessageNotification;
use App\Mail\NewRequestNotification;
use App\Mail\NewUserNotification;
use App\Mail\StatusChangeNotification;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $details;

    /**
     * Create a new job instance.
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (empty($this->details['type'])) {
            Log::error('Detalles de correo incompletos', $this->details);
            return;
        }

        if (empty($this->details['recipients'])) {
            return;
        }

        try {
            $recipients = $this->details['recipients'];
            $mail = $this->getMailInstance();

            if ($mail) {
                Mail::to($recipients)->send($mail);
            }
        } catch (Exception $e) {
            Log::error('Error al enviar correo: ' . $e->getMessage(), $this->details);
        }
    }

    /**
     * Get the mail instance based on the email type.
     */
    private function getMailInstance(): StatusChangeNotification|NewUserNotification|NewRequestNotification|NewMessageNotification|null
    {
        switch ($this->details['type']) {
            case 'createRequest':
                return new NewRequestNotification($this->details['request']);
            case 'statusChange':
                return new StatusChangeNotification($this->details['request']);
            case 'createUser':
                return new NewUserNotification($this->details['user'], $this->details['password']);
            case 'createMessage':
                return new NewMessageNotification($this->details['messageModel']);
            default:
                Log::warning('Tipo de correo no reconocido', ['type' => $this->details['type']]);
                return null;
        }
    }
}