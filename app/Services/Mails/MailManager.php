<?php

namespace App\Services\Mails;

use App\Jobs\SendEmail;
use App\Mail\Requests\NewMessageNotification;
use App\Mail\Requests\NewRequestNotification;
use App\Mail\Requests\StatusChangeNotification;
use App\Mail\NewUserNotification;
use App\Models\Message;
use App\Models\RequestModel;
use App\Models\Setting;
use App\Models\User;

class MailManager
{
    public static function sendNewRequestNotification(RequestModel $requestModel): void
    {
        $recipients = Setting::dataBag('emailNotifications')->array('createRequest');

        SendEmail::dispatch($recipients, new NewRequestNotification($requestModel));
    }

    public static function sendStatusChangeNotification(RequestModel $requestModel): void
    {
        $emails = Setting::dataBag('emailNotifications.statusChange')->array($requestModel->status->value);

        $recipients = [
            ...[$requestModel->user->email],
            ...$emails,
        ];

        SendEmail::dispatch($recipients, new StatusChangeNotification($requestModel));
    }

    public static function sendNewUserNotification(User $user, string $password): void
    {
        $recipients = [$user->email];

        SendEmail::dispatch($recipients, new NewUserNotification($user, $password));
    }

    public static function sendNewMessageNotification(Message $messageModel): void
    {
        $recipients = [$messageModel->request->user->email];

        SendEmail::dispatch($recipients, new NewMessageNotification($messageModel));
    }
}
