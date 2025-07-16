<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChangeUserStatusController extends Controller
{
    public function __invoke(Request $request, User $user)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);
        $user->update([
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->back();

    }
}
