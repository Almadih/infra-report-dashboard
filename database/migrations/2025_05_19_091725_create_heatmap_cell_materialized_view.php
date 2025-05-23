<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        DB::statement('
    CREATE MATERIALIZED VIEW mv_heatmap_cells AS
     SELECT
        -- Deterministic ID based on geometry, as the date range is implicit (last 7 days)
        MD5(ST_AsText(ST_SnapToGrid(r.location, 0.01, 0.01)))::uuid AS cell_id,
        ST_SnapToGrid(r.location, 0.01, 0.01) AS geom,
        COUNT(*) AS report_count,
        -- This records the date for which this "last 7 days" calculation is valid
        -- (i.e., the end date of the 7-day period)
        CURRENT_DATE AS data_until_date
    FROM
        reports r
    WHERE
        -- Data from today (at time of refresh) and the 6 previous days
        r.created_at >= (CURRENT_DATE - INTERVAL \'6 days\')
        AND r.created_at < (CURRENT_DATE + INTERVAL \'1 day\') -- Ensures we get full days up to end of CURRENT_DATE
    GROUP BY
        geom;
');

        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS uidx_mv_heatmap_cells_cell_id ON mv_heatmap_cells (cell_id);');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_mv_heatmap_cells_week_geom ON mv_heatmap_cells (cell_id, geom);');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_mv_heatmap_cells_geom_gist ON mv_heatmap_cells USING GIST (geom);');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX idx_mv_heatmap_cells_unique');
        DB::statement('DROP INDEX idx_mv_heatmap_cells_week_geom');
        DB::statement('DROP INDEX idx_mv_heatmap_cells_geom_gist');
        DB::statement('DROP MATERIALIZED VIEW mv_heatmap_cells');
    }
};
