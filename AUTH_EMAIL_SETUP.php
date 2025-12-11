<?php

/**
 * Integration Guide: Using EmailQueueService for Auth Notifications
 *
 * To route password reset and other auth notifications through EmailQueueService,
 * add this method to your User model (app/Models/User.php):
 *
 *     use App\Notifications\ResetPasswordNotification;
 *
 *     public function sendPasswordResetNotification($token)
 *     {
 *         $this->notify(new ResetPasswordNotification($token));
 *     }
 *
 * This override replaces Laravel's default password reset notification with one
 * that queues through your messagequeue stored procedure via EmailQueueService.
 *
 * The ResetPasswordNotification class (app/Notifications/ResetPasswordNotification.php)
 * handles the actual queueing.
 *
 * Optional: For other auth notifications (email verification, etc.), create similar
 * notification classes and override the corresponding send methods in User.
 */
