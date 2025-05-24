<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FCMTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validate = $request->validate([
            'fcm_token' => ['required', 'string'],
        ]);

        $request->user()->fcm_token = $validate['fcm_token'];

        $request->user()->save();

        return response()->json([
            'message' => 'fcm token stored',
        ]);

    }
}
