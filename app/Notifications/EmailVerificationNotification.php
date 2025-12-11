<?php

namespace App\Notifications;

use App\Services\EmailQueueService;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
{
    protected $verificationUrl;

    public function __construct(string $verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    public function via($notifiable)
    {
        return ['custom'];
    }

    public function toCustom($notifiable)
    {
        $subject = 'Verify Your Email Address';

        $bodyLines = [
            'Please click the link below to verify your email address:',
            $this->verificationUrl,
            '',
            'If you did not create an account, no further action is required.',
        ];

        $body = implode("\r\n", $bodyLines);

        EmailQueueService::queueMessage($notifiable->email, $subject, $body);
    }
}
