<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{

    /**
     * The password reset token.
     */
    public string $token;

    /**
     * The callback that should be used to build the mail message.
     */
    public static $toMailCallback;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildMailMessage($notifiable);
    }

    /**
     * Build the mail message.
     */
    protected function buildMailMessage(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject(__('Reset Password Notification'))
            ->markdown('emails.password-reset', [
                'actionUrl' => $resetUrl,
                'actionText' => __('Reset Password'),
                'displayName' => $notifiable->name ?? $notifiable->email,
            ]);
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     */
    public static function toMailUsing($callback): void
    {
        static::$toMailCallback = $callback;
    }
}
