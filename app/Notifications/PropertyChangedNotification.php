<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyChangedNotification extends Notification
{
    use Queueable;

    public $property;
    public $action;
    public $changes;

    /**
     * Create a new notification instance.
     */
    public function __construct($property, $action, $changes = [])
    {
        $this->property = $property;
        $this->action = $action;
        $this->changes = $changes;
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
        $actionText = ucfirst($this->action);
        $message = (new MailMessage)
            ->subject("Property {$actionText}: {$this->property->name}")
            ->line("The property '{$this->property->name}' has been {$this->action}.");

        if (!empty($this->changes)) {
            $message->line('Changes made:');
            foreach ($this->changes as $key => $value) {
                if ($key !== 'updated_at') {
                    $message->line("- **" . ucfirst($key) . "**: {$value}");
                }
            }
        }

        return $message
            ->action('View Properties', url("/properties"))
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
            'property_id' => $this->property->id,
            'name' => $this->property->name,
            'action' => $this->action,
            'message' => "Property '{$this->property->name}' was {$this->action}.",
            'changes' => $this->changes,
        ];
    }
}
