<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestStatusChanged extends Notification
{
    use Queueable;

    private string $bookTitle;

    private string $status;

    public function __construct(string $bookTitle, string $status)
    {
        $this->bookTitle = $bookTitle;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => "Your exchange request for '{$this->bookTitle}' has been {$this->status}.",
            'book_title' => $this->bookTitle,
            'status' => $this->status,
        ];
    }
}
