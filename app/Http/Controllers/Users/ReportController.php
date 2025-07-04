<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Status;
use App\Services\ReputationService;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $lng = $request->query('lng');
        $lat = $request->query('lat');
        $radius = $request->query('radius');

        $reports = Report::with(['damageType', 'status', 'severity', 'images'])->whereRaw('ST_DWithin(location, ST_SetSRID(ST_MakePoint(?, ?), 4326), ?)',
            [$lng, $lat, $radius / 111320.0])->get();

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

        return response()->json($report);

    }

    public function show(Report $report)
    {
        $report->load(['severity', 'status', 'damageType', 'images']);

        return response()->json($report);
    }
}
