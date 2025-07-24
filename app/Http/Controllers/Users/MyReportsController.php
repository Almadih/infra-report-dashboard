<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MyReportsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $reports = Report::with(['status', 'damageType', 'severity', 'images', 'flags', 'updates'])->where('user_id', Auth::user()->id)->get();

        return response()->json($reports);
    }
}
