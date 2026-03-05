<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmail;
use App\Models\Configuration;
use App\Models\Message;
use App\Models\RequestModel;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class MailManager
{
    /**
     * Método genérico para enviar notificaciones por correo electrónico.
     */
    private static function sendEmail(array $mailDetails): void
    {
        try {
            SendEmail::dispatch($mailDetails);
        } catch (Exception $exception) {
            Log::error('Error al enviar correo: ' . $exception->getMessage(), $mailDetails);
        }
    }

    public static function sendNewRequestNotification(RequestModel $requestModel): void
    {
        $emailNotifications = Configuration::getValue('emailNotifications', []);
        $recipients = $emailNotifications['createRequest'] ?? [];

        $mailDetails = [
            'type' => 'createRequest',
            'recipients' => $recipients,
            'request' => $requestModel,
        ];

        self::sendEmail($mailDetails);
    }

    public static function sendStatusChangeNotification(RequestModel $requestModel): void
    {
        $emailNotifications = Configuration::getValue('emailNotifications', []);
        $statusChange = $emailNotifications['statusChange'] ?? [];
        $emails = $statusChange[$requestModel->status] ?? [];

        $recipients = array_merge([$requestModel->user->email], $emails);

        $mailDetails = [
            'type' => 'statusChange',
            'recipients' => $recipients,
            'request' => $requestModel,
        ];

        self::sendEmail($mailDetails);
    }

    public static function sendNewUserNotification(User $user, string $password): void
    {
        $recipients = [$user->email];

        $mailDetails = [
            'type' => 'createUser',
            'recipients' => $recipients,
            'user' => $user,
            'password' => $password,
        ];

        self::sendEmail($mailDetails);
    }

    public static function sendNewMessageNotification(Message $messageModel): void
    {
        $recipients = [$messageModel->request->user->email];

        $mailDetails = [
            'type' => 'createMessage',
            'recipients' => $recipients,
            'messageModel' => $messageModel,
        ];

        self::sendEmail($mailDetails);
    }
}
