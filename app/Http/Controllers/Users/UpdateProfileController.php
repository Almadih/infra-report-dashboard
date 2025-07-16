<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'show_info_to_public' => ['required', 'boolean'],
        ]);

        Log::debug(json_encode($validated));

        Auth::user()->update($validated);

        return response()->json(Auth::user());
    }
}
