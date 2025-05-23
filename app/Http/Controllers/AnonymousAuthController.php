<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Str;

class AnonymousAuthController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string|max:255',
        ]);

        $deviceId = $request->input('device_id');

        // Attempt to find the user by device_id
        $user = User::where('device_id', $deviceId)->first();

        if (! $user) {
            // User does not exist, create a new anonymous user
            $user = User::create([
                'device_id' => $deviceId,
                'name' => 'User '.Str::limit($deviceId, 10, ''), // Or some other convention
                // Email needs to be unique. Using device_id ensures this.
                // Or make email nullable in DB and don't set it.
                'email' => $deviceId.'@user.com',
                // Password can be a random string as it won't be used for login
                // Or make password nullable in DB.
                'password' => Hash::make(Str::random(16)),
                'is_anonymous' => true,
            ]);
        }

        // Revoke all old tokens (optional, good for security if a device is re-initiated)
        // $user->tokens()->delete();

        // Create a new token for the user
        // You can name the token for easier identification if needed
        $token = $user->createToken($deviceId.'-DeviceToken')->plainTextToken;

        return response()->json([
            'message' => 'Device initiated successfully.',
            'user' => $user, // Optional: you might want to send user_id
            'access_token' => $token,
        ]);
    }
}
