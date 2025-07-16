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
        $user->loadCount(['reports', 'reports as flagged_reports' => function ($q) {
            $q->whereHas('flags', function ($q) {
                $q->where('confirmed_by_admin', true);
            });
        }]);

        return Inertia::render('Users/Show', [
            'user' => $user,
            'reputationHistory' => $user->reputationHistory()->paginate(10)->withQueryString(),
        ]);
    }
}
