<?php

namespace App\Notifications;

use App\Services\EmailQueueService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Send the notification using EmailQueueService instead of default mail driver.
     *
     * Override to queue through the messagequeue stored procedure.
     */
    public function via($notifiable)
    {
        return ['custom'];
    }

    /**
     * Custom channel handler: queue via EmailQueueService.
     */
    public function toCustom($notifiable)
    {
        $resetUrl = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        $subject = 'Reset Password Notification';

        $bodyLines = [
            'You are receiving this email because we received a password reset request for your account.',
            '',
            'Click the link below to reset your password:',
            $resetUrl,
            '',
            'This password reset link will expire in 60 minutes.',
            '',
            'If you did not request a password reset, no further action is required.',
        ];

        $body = implode("\r\n", $bodyLines);

        EmailQueueService::queueMessage(
            $notifiable->getEmailForPasswordReset(),
            $subject,
            $body
        );
    }
}
