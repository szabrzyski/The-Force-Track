<?php

namespace App\Notifications;

use App\Mail\IssueStatusChangedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class IssueStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public $tries = 120;

    public $backoff = 60;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public string $newStatus, public string $issueId)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

/**
 * Get the mail representation of the notification.
 *
 * @param  mixed  $notifiable
 * @return Mailable
 */
public function toMail($notifiable)
{
    return (new IssueStatusChangedEmail($this->newStatus, $this->issueId))
                ->to($notifiable->email);
}

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'newStatus' => $this->newStatus,
            'issueId' => $this->issueId,
        ];
    }
}
