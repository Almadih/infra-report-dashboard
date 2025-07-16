<?php

namespace App\Http\Controllers;

use App\Filters\DateRangeFilter;
use App\Filters\FilterRelationIn;
use App\Models\Report;
use DB;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportsMapController extends Controller
{
    public function __invoke(Request $request)
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

        $ids = $reports->pluck('id')->toArray();
        $center = DB::table('reports')
            ->selectRaw('ST_AsGeoJSON(ST_Centroid(ST_Collect(ST_Transform(location, 4326)))) AS center')
            ->whereIn('id', $ids)
            ->first();

        $nestedFilters = [
            'severity' => $request->input('filter.severity'),
            'status' => $request->input('filter.status'),
            'date' => $request->input('filter.date'),
        ];

        return Inertia::render('ReportsMap', [
            'reports' => $reports,
            'filters' => $nestedFilters,
            'center' => json_decode($center->center),
        ]);

    }
}
