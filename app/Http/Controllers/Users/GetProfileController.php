<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GetProfileController extends Controller
{
    public function __invoke()
    {
        return response()->json(Auth::user());
    }
}
