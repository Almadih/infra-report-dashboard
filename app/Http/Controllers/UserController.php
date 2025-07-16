<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Users/Index', [
            'users' => User::where('is_anonymous', true)->orderBy('reputation', 'desc')->withCount('reports')->paginate(10),
        ]);
    }

    public function show(Request $request, User $user)
    {
        return Inertia::render('Users/Show', [
            'user' => $user,
        ]);
    }
}
