<?php

namespace App\Notifications;

use App\Services\EmailQueueService;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return [\App\Notifications\Channels\EmailQueueChannel::class];
    }

    public function toCustom($notifiable)
    {
        $subject = "Welcome to Manifest Portal";

        $bodyLines = [
            "Hello {$this->user->name},",
            '',
            'An account has been created for you on the Manifest Portal.',
            "Email: {$this->user->email}",
            '',
            'If you did not expect this, contact your administrator.',
        ];

        $body = implode("\r\n", $bodyLines);

        EmailQueueService::queueMessage($this->user->email, $subject, $body);
    }
}
