<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Services\EmailQueueService;

class EmailQueueChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        // If the notification implements a custom toCustom method, call it.
        if (method_exists($notification, 'toCustom')) {
            return $notification->toCustom($notifiable);
        }

        // Fallback: if notification provides a simple message payload via toArray(),
        // queue a generic email using EmailQueueService.
        if (method_exists($notification, 'toArray')) {
            $data = $notification->toArray($notifiable);
            $to = $notifiable->getEmailForPasswordReset() ?? ($notifiable->email ?? null);
            $subject = $data['subject'] ?? 'Notification';
            $body = $data['body'] ?? json_encode($data);
            if ($to) {
                return EmailQueueService::queueMessage($to, $subject, $body);
            }
        }

        // Nothing to send
        return null;
    }
}
