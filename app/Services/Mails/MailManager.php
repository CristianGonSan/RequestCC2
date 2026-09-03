<?php

namespace App\Services\Mails;

use App\Jobs\SendEmail;
use App\Mail\MoneyRequests\NewMessageNotification;
use App\Mail\MoneyRequests\NewRequestNotification;
use App\Mail\MoneyRequests\StatusChangeNotification;
use App\Mail\NewUserNotification;
use App\Models\Message;
use App\Models\MoneyRequests\MoneyRequest;
use App\Models\Setting;
use App\Models\User;

class MailManager
{
    public static function sendNewRequestNotification(MoneyRequest $moneyRequest): void
    {
        $recipients = Setting::dataBag('emailNotifications')->array('createRequest');

        SendEmail::dispatch($recipients, new NewRequestNotification($moneyRequest));
    }

    public static function sendStatusChangeNotification(MoneyRequest $moneyRequest): void
    {
        $emails = Setting::dataBag('emailNotifications.statusChange')->array($moneyRequest->status->value);

        $recipients = [
            ...[$moneyRequest->user->email],
            ...$emails,
        ];

        SendEmail::dispatch($recipients, new StatusChangeNotification($moneyRequest));
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
