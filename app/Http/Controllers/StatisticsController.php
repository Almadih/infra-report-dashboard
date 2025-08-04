<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Severity;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StatisticsController extends Controller
{
    public function __invoke(Request $request)
    {
        $timeRange = $request->get('timeRange') ?? '30days';

        $startDate = Carbon::now(); // e.g., 1 month ago
        $endDate = Carbon::now();

        switch ($timeRange) {
            case '7days':
                $startDate = $startDate->subDays(7);
                break;
            case '30days':
                $startDate = $startDate->subDays(30);
                break;
            case '90days':
                $startDate = $startDate->subDays(90);
                break;
            case '1year':
                $startDate = $startDate->subDays(365);
                break;
        }

        $statuses = Status::all();
        $severities = Severity::all();
        $reportsByStatus = Report::select(DB::raw('count(*)'), 'status_id')->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->groupBy('status_id')->get()->map(function ($report) use ($statuses) {
            $status = $statuses->find($report->status_id);

            return [
                'name' => $status->name,
                'value' => $report->count,
            ];
        });
        $reportsBySeverity = Report::select(DB::raw('count(*)'), 'severity_id')->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->groupBy('severity_id')->get()->map(function ($report) use ($severities) {
            $severity = $severities->find($report->severity_id);

            return [
                'name' => $severity->name,
                'value' => $report->count,
            ];
        });

        $topReporters = User::where('is_anonymous', true)->withCount(['reports' => function ($q) use ($startDate, $endDate) {
            $q->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
        }])->orderBy('reports_count', 'desc')->limit(6)->get();

        $reportsVolume = DB::select("
    SELECT 
        d::date AS day,
        COUNT(r.id) AS report_count
    FROM 
        generate_series(
          ?::date,
            ?::date,
            INTERVAL '1 day'
        ) AS d
    LEFT JOIN reports r ON DATE(r.created_at) = d
    GROUP BY d
    ORDER BY d
", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        $reportsVolumeBySeverity = DB::select("
    SELECT 
        d::date AS day,
        s.id AS severity_id,
        s.name AS severity_name,
        COUNT(r.id) AS report_count
    FROM 
        generate_series(?::date, ?::date, INTERVAL '1 day') AS d
    CROSS JOIN 
        severities s
    LEFT JOIN 
        reports r 
        ON DATE(r.created_at) = d AND r.severity_id = s.id
    GROUP BY 
        d, s.id, s.name
    ORDER BY 
        d, s.id
", [$startDate, $endDate]);

        $reportsByRegion = DB::select('
    SELECT 
        c.name_2 AS name,
        COUNT(r.id) AS count
    FROM 
        cities c
    JOIN 
        reports r
        ON ST_Contains(c.geom, r.location)
        where r.created_at >= ? and r.created_at <= ?
    GROUP BY 
        c.name_2
    ORDER BY 
        count DESC
', [$startDate, $endDate]);

        $query = Report::query()->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);

        $stats = [
            'total_reports' => $query->count(),
            'open_reports' => $query->where('status_id', Status::where('name', 'pending')->first()->id)->count(),
            'active_reporters' => User::query()->where('is_anonymous', true)->where('is_active', true)->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->count(),
        ];

        dd($reportsVolumeBySeverity);

        return Inertia::render('Statistics/Index', [
            'reportsByStatus' => $reportsByStatus,
            'reportsBySeverity' => $reportsBySeverity,
            'topReporters' => $topReporters,
            'reportsVolume' => $reportsVolume,
            'reportsVolumeBySeverity' => $reportsVolumeBySeverity,
            'reportsByRegion' => $reportsByRegion,
            'timeRange' => $timeRange,
            'stats' => $stats,
        ]);

    }
}
