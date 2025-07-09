<?php

namespace App\Http\Controllers;

use App\Models\ReportFlag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportFlagController extends Controller
{
    public function index(Request $request)
    {
        $flags = QueryBuilder::for(ReportFlag::class)
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::exact('confirmed_by_admin'), // Use exact match for the boolean field
                AllowedFilter::exact('auto_flagged'),       // Use exact match for the boolean field
            ])
            ->allowedIncludes(['report', 'duplicatedReport'])
            ->latest()
            ->paginate()
            ->withQueryString();

        return Inertia::render('ReportFlags/Index', [
            'flags' => $flags,
            'filters' => [
                'type' => $request->input('filter.type'),
                // Map backend values back to frontend values for initial state
                'status' => match ($request->input('filter.confirmed_by_admin')) {
                    'true' => 'confirmed',
                    'false' => 'pending',
                    default => null,
                },
                'flagged_by' => match ($request->input('filter.auto_flagged')) {
                    'true' => 'auto',
                    'false' => 'manual',
                    default => null,
                },
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_id' => ['required', 'string'],
            'type' => ['required', 'string', 'in:low_quality,duplicate'],
            'duplicated_report_id' => ['nullable', 'string', 'required_if:type,duplicate', 'exists:reports,id'],
            'reason' => ['nullable', 'string'],
        ]);

        $flag = ReportFlag::create([
            'report_id' => $validated['report_id'],
            'type' => $validated['type'],
            'duplicated_report_id' => $validated['duplicated_report_id'],
            'reason' => $validated['reason'],
            'auto_flagged' => false,
            'confirmed_by_admin' => true,
        ]);

        return redirect()->back();
    }

    public function show(ReportFlag $ReportFlag)
    {

        $ReportFlag->load(['report', 'duplicatedReport']);

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
