<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;

class ReadOneNotificationController extends Controller
{
    public function __invoke(DatabaseNotification $notification)
    {
        $notification->markAsRead();

        return response()->json($notification);
    }
}
