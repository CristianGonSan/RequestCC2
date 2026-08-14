<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public array $recipients,
        public Mailable $mailable,
    ) {}

    public function handle(): void
    {
        if (empty($this->recipients)) {
            return;
        }

        try {
            Mail::to($this->recipients)->send($this->mailable);
        } catch (Exception $exception) {
            Log::error('Error al enviar correo: '.$exception->getMessage(), [
                'recipients' => $this->recipients,
                'mailable'   => $this->mailable::class,
            ]);
        }
    }
}
