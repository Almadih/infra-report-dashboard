<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Middleware\ActiveUserMiddleware;
use App\Jobs\CheckDuplicatedReportJob;
use App\Jobs\CheckReportQualityJob;
use App\Models\Report;
use App\Models\Status;
use App\Services\ReputationService;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Database\PostgisFunctions\ST;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(ActiveUserMiddleware::class, only: ['store']),
        ];
    }

    public function index(Request $request)
    {
        $request->validate([
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'radius' => ['required', 'numeric'], // in meters,
        ]);

        $centerPoint = Point::makeGeodetic($request->lat, $request->lng);

        $reports = Report::with(['damageType', 'status', 'severity', 'images', 'flags' => function ($q) {
            $q->where('confirmed_by_admin', true);
        }, 'updates', 'user' => function ($query) {
            $query->where(function ($q) {
                $q->where('show_info_to_public', true)->orWhere('id', Auth::user()->id);
            })->select('id', 'name', 'reputation');
        }])
            ->where(ST::dWithinGeography('location', $centerPoint, $request->radius), true)
            ->get();

        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'damage_type_id' => ['required', 'exists:damage_types,id'],
            'severity_id' => ['required', 'exists:severities,id'],
            'images' => ['required', 'array'],
            'images.*' => ['required', 'file'],
            'location.lat' => ['required'],
            'location.lng' => ['required'],
            'description' => ['nullable'],
            'address' => ['required'],
        ]);

        $status = Status::where('name', 'pending')->first();

        $report = Report::create([
            'address' => $validated['address'],
            'description' => $validated['description'],
            'damage_type_id' => $validated['damage_type_id'],
            'severity_id' => $validated['severity_id'],
            'user_id' => Auth::user()->id,
            'status_id' => $status->id,
            'location' => Point::makeGeodetic($validated['location']['lat'], $validated['location']['lng']),
        ]);

        foreach ($request->file('images') as $image) {
            $path = $image->store("reports/{$report->id}", 'private');
            $report->images()->create([
                'path' => $path,
            ]);
        }

        ReputationService::addReputationHistory($report, ReputationService::TYPE_SUBMIT);

        CheckDuplicatedReportJob::dispatch($report);
        CheckReportQualityJob::dispatch($report);

        return response()->json($report);

    }

    public function show(Report $report)
    {
        $report->load(['severity', 'status', 'damageType', 'images', 'updates', 'flags', 'user' => function ($query) {
            $query->where(function ($q) {
                $q->where('show_info_to_public', true)->orWhere('id', Auth::user()->id);
            })->select('id', 'name', 'reputation');
        }]);

        return response()->json($report);
    }
}
