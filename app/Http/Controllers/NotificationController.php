<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read']);
    }
}
