<?php

namespace App\Http\Controllers;

use App\Models\ReportFlag;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportFlagController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('ReportFlags/Index', [
            'flags' => ReportFlag::orderBy('created_at', 'desc')->paginate(20),
        ]);
    }

    public function show(ReportFlag $ReportFlag)
    {

        // $ReportFlag->load(['report', 'duplicatedReport']);

        return Inertia::render('ReportFlags/Show', [
            'flag' => $ReportFlag,
        ]);
    }

    public function update(ReportFlag $ReportFlag, Request $request)
    {
        $validated = $request->validate([
            'confirmed_by_admin' => ['required', 'boolean'],
            'reason' => ['nullable', 'string'],
        ]);

        $ReportFlag->update([
            'confirmed_by_admin' => $validated['confirmed_by_admin'],
            'reason' => $validated['reason'],
        ]);

        return redirect()->back();
    }

    public function destroy(ReportFlag $ReportFlag)
    {

        $ReportFlag->delete();

        return redirect(route('report-flags.index'));
    }
}
