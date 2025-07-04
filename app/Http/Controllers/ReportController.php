<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Severity;
use App\Models\Status;
use App\Notifications\ReportStatusNotification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $defaultFilters = [
            'critical' => true,
            'high' => true,
            'medium' => true,
            'low' => true,
            'pending' => true,
            'under_review' => true,
            'verified' => true,
            'resolved' => true,
            'location' => '',
            'date_start' => '',
            'date_end' => '',
        ];

        $filters = array_merge($defaultFilters, $request->only(array_keys($defaultFilters)));

        $query = Report::query();

        // Filter by severity
        $activeSeverityFilter = collect(['critical', 'high', 'medium', 'low'])->filter(function ($severity) use ($filters) {
            return filter_var($filters[$severity], FILTER_VALIDATE_BOOLEAN);
        });

        $query->whereIn('severity_id', Severity::whereIn('name', $activeSeverityFilter)->pluck('id'));

        // Filter by status
        $activeStatusFilter = collect(['pending', 'under_review', 'verified', 'resolved'])->filter(function ($status) use ($filters) {
            return filter_var($filters[$status], FILTER_VALIDATE_BOOLEAN);
        });

        $query->whereIn('status_id', Status::whereIn('name', $activeStatusFilter)->pluck('id'));

        // dd($filters);

        // Date range filter
        if ($filters['date_start']) {
            $query->whereDate('created_at', '>=', $filters['date_start']);
        }
        if ($filters['date_end']) {
            $query->whereDate('created_at', '<=', $filters['date_end']);
        }

        $items = $query->with(['damageType', 'severity', 'status'])->paginate(50);

        // Reconstruct nested filters to send to Vue
        $nestedFilters = [
            'severity' => [
                'critical' => filter_var($filters['critical'], FILTER_VALIDATE_BOOLEAN),
                'high' => filter_var($filters['high'], FILTER_VALIDATE_BOOLEAN),
                'medium' => filter_var($filters['medium'], FILTER_VALIDATE_BOOLEAN),
                'low' => filter_var($filters['low'], FILTER_VALIDATE_BOOLEAN),
            ],
            'status' => [
                'pending' => filter_var($filters['pending'], FILTER_VALIDATE_BOOLEAN),
                'under_review' => filter_var($filters['under_review'], FILTER_VALIDATE_BOOLEAN),
                'verified' => filter_var($filters['verified'], FILTER_VALIDATE_BOOLEAN),
                'resolved' => filter_var($filters['resolved'], FILTER_VALIDATE_BOOLEAN),
            ],
            'location' => $filters['location'],
            'date' => [
                'start' => $filters['date_start'],
                'end' => $filters['date_end'],
            ],
        ];

        return Inertia::render('Reports/Index', [
            'reports' => $items->withQueryString(),
            'filters' => $nestedFilters,
        ]);
    }

    public function show(Report $report)
    {
        $report->load(['severity', 'status', 'damageType', 'images', 'updates']);

        return Inertia::render('Reports/Show', [
            'report' => $report,
            'statuses' => Status::all(),
        ]);
    }

    public function update(Report $report, Request $request)
    {
        $report->load(['user', 'status']);
        $validated = $request->validate([
            'status_id' => ['required', 'exists:statuses,id'],
        ]);

        $report->update([
            'status_id' => $validated['status_id'],
        ]);

        $report->user->notify(new ReportStatusNotification($report));

        return redirect(route('reports.show', $report->id));

    }
}
