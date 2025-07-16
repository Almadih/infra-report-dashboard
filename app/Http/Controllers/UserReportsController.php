<?php

namespace App\Http\Controllers;

use App\Filters\DateRangeFilter;
use App\Filters\FilterRelationIn;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserReportsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user)
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
            ->where('user_id', $user->id)
            ->latest()
            ->paginate()
            ->withQueryString();

        $nestedFilters = [
            'severity' => $request->input('filter.severity'),
            'status' => $request->input('filter.status'),
            'date' => $request->input('filter.date'),
        ];

        return Inertia::render('Users/Reports', [
            'user' => $user,
            'reports' => $reports,
            'filters' => $nestedFilters,
        ]);
    }
}
