<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReadNotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json();
    }
}
