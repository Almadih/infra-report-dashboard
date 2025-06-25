<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class StoreReportUpdateController extends Controller
{
    public function __invoke(Request $request, Report $report)
    {

        $request->validate([
            'text' => ['required'],
        ]);

        $report->updates()->create([
            'text' => $request->text,
        ]);

        return redirect()->back();

    }
}
