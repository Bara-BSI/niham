<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark all unread notifications as read for the authenticated user.
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back();
    }

    /**
     * Clear all notifications for the authenticated user.
     */
    public function clearAll(Request $request)
    {
        $request->user()->notifications()->delete();
        return back();
    }
}
