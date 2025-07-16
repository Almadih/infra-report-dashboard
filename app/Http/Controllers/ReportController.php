<?php

namespace App\Http\Controllers;

use App\Filters\DateRangeFilter;
use App\Filters\FilterRelationIn;
use App\Models\Report;
use App\Models\Status;
use App\Notifications\ReportStatusNotification;
use App\Services\ReputationService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $reports = QueryBuilder::for(Report::class)
            ->allowedFilters([
                AllowedFilter::custom('status', new FilterRelationIn('status', 'name')),
                AllowedFilter::custom('severity', new FilterRelationIn('severity', 'name')),
                AllowedFilter::custom('damage_type', new FilterRelationIn('damage_type', 'name')),
                AllowedFilter::custom('date', new DateRangeFilter('created_at')),
            ])
            ->with([
                'status', 'severity', 'damageType',
            ])
            ->latest()
            ->paginate()
            ->withQueryString();

        $nestedFilters = [
            'severity' => $request->input('filter.severity'),
            'status' => $request->input('filter.status'),
            'date' => $request->input('filter.date'),
        ];

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'filters' => $nestedFilters,
        ]);
    }

    public function show(Report $report)
    {
        $report->load(['severity', 'status', 'damageType', 'images', 'updates', 'flags', 'user']);

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

        $status = Status::find($validated['status_id']);
        $reputationHistoryType = '';
        switch ($status->name) {
            case 'verified':
                $reputationHistoryType = ReputationService::TYPE_VERIFIED;
                break;
            case 'resolved':
                $reputationHistoryType = ReputationService::TYPE_RESOLVE;
                break;
            default:
                break;
        }

        // $report->update([
        //     'status_id' => $validated['status_id'],
        // ]);
        if ($reputationHistoryType) {
            ReputationService::addReputationHistory($report, $reputationHistoryType);
        }

        $report->user->notify(new ReportStatusNotification($report));

        return redirect(route('reports.show', $report->id));

    }
}
