<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class ReportsHeatmapController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $rows = DB::select("
    SELECT
      ST_AsGeoJSON(geom)::json AS geometry,
      jsonb_build_object(
          'report_count', report_count
      ) AS properties
    FROM mv_heatmap_cells
");

        $center = DB::select('SELECT ST_AsGeoJSON(ST_Centroid(ST_Collect(geom))) as center FROM mv_heatmap_cells');
        $centerObject = empty($center) ? null : json_decode($center[0]->center);

        return response()->json([
            'rows' => $rows,
            'center' => $centerObject,
        ]);
    }
}
