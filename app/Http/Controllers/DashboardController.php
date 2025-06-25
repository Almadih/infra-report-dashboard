<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia; // For type hinting

class DashboardController extends Controller
{
    // Adjust these based on your actual Status and Severity model/data
    private const STATUS_PENDING = 'pending';

    private const SEVERITY_CRITICAL = 'critical';

    private const STATUS_RESOLVED = 'resolved';

    public function __invoke()
    {
        $now = Carbon::now();

        $stats = [
            'totalReports' => $this->getStatsForTotalReports($now),
            'todaysReports' => $this->getStatsForTodaysReports($now),
            'pendingReports' => $this->getStatsForPendingReports($now),
            'criticalReports' => $this->getStatsForCriticalReports(),
        ];

        $latestReports = Report::with(['damageType', 'severity', 'status'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return Inertia::render('Dashboard', [
            'reports' => $latestReports,
            'stats' => $stats,
        ]);
    }

    private function getStatsForTotalReports(Carbon $now): array
    {
        $totalCount = Report::count();

        $newThisMonth = Report::whereBetween('created_at', [
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth(),
        ])->count();

        $newLastMonth = Report::whereBetween('created_at', [
            $now->copy()->subMonthNoOverflow()->startOfMonth(),
            $now->copy()->subMonthNoOverflow()->endOfMonth(),
        ])->count();

        return [
            'value' => $totalCount,
            'details' => $this->calculatePercentageDetails($newThisMonth, $newLastMonth, 'from last month'),
        ];
    }

    private function getStatsForTodaysReports(Carbon $now): array
    {
        $todayCount = Report::whereDate('created_at', $now->toDateString())->count();
        $yesterdayCount = Report::whereDate('created_at', $now->copy()->subDay()->toDateString())->count();

        return [
            'value' => $todayCount,
            'details' => $this->calculateAbsoluteDetails($todayCount, $yesterdayCount, 'since yesterday'),
        ];
    }

    private function getStatsForPendingReports(Carbon $now): array
    {
        // Total currently pending reports
        $currentPendingCount = Report::whereHas('status', function (Builder $query) {
            $query->where('name', self::STATUS_PENDING); // Adjust 'name' if your column is different
        })->count();

        // For change text: "newly pending" this week vs last week
        // This assumes reports are created with 'Pending' status or transition quickly.
        // If status changes are tracked with timestamps, a more accurate query could be made.
        $pendingThisWeek = Report::whereHas('status', function (Builder $query) {
            $query->where('name', self::STATUS_PENDING);
        })->whereBetween('created_at', [ // Or use a 'status_updated_at' if available for transitions
            $now->copy()->startOfWeek(),
            $now->copy()->endOfWeek(),
        ])->count();

        $pendingLastWeek = Report::whereHas('status', function (Builder $query) {
            $query->where('name', self::STATUS_PENDING);
        })->whereBetween('created_at', [ // Or use a 'status_updated_at'
            $now->copy()->subWeek()->startOfWeek(),
            $now->copy()->subWeek()->endOfWeek(),
        ])->count();

        return [
            'value' => $currentPendingCount,
            'details' => $this->calculatePercentageDetails($pendingThisWeek, $pendingLastWeek, 'from last week'),
        ];
    }

    private function getStatsForCriticalReports(): array
    {
        $criticalCount = Report::whereHas('severity', function (Builder $query) {
            $query->where('name', self::SEVERITY_CRITICAL); // Adjust 'name' if your column is different
        })->whereHas('status', function (Builder $query) {
            $query->whereNot('name', self::STATUS_RESOLVED);
        })->count();

        return [
            'value' => $criticalCount,
            'details' => 'Requires immediate attention', // Static as per original
        ];
    }

    private function calculatePercentageDetails(int $current, int $previous, string $periodText): string
    {
        if ($previous == 0) {
            if ($current > 0) {
                return "+100% {$periodText}"; // Or "New" or some other appropriate text
            }

            return "0% {$periodText}"; // No change from zero
        }

        $percentageChange = (($current - $previous) / $previous) * 100;
        $formattedPercentage = round($percentageChange);

        return ($formattedPercentage >= 0 ? '+' : '').$formattedPercentage."% {$periodText}";
    }

    private function calculateAbsoluteDetails(int $current, int $previous, string $periodText): string
    {
        $difference = $current - $previous;

        return ($difference >= 0 ? '+' : '').$difference." {$periodText}";
    }
}
