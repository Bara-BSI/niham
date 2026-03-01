<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssetChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $asset, public $action)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($notifiable->notify_email && $notifiable->email_frequency === 'immediate') {
            return ['database', 'mail'];
        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $actionText = ucfirst((string) $this->action);
        return (new MailMessage)
            ->subject("Asset {$actionText}: {$this->asset->name}")
            ->line("The asset '{$this->asset->name}' (Tag: {$this->asset->tag}) has been {$this->action}.")
            ->action('View Asset', url("/assets/{$this->asset->id}"))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'asset_id' => $this->asset->id,
            'tag' => $this->asset->tag,
            'name' => $this->asset->name,
            'action' => $this->action,
            'message' => "Asset '{$this->asset->name}' was {$this->action}."
        ];
    }
}
