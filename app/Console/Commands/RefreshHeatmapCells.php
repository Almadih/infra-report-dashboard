<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Log;

class RefreshHeatmapCells extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-heatmap-cells';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh heatmap cells materialized view.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Refreshing heatmap cells materialized view...');
        DB::statement('REFRESH MATERIALIZED VIEW CONCURRENTLY mv_heatmap_cells;');
    }
}
