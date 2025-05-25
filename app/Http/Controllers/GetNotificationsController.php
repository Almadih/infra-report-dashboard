<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetNotificationsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return $request->user()->notifications()->get();
    }
}
