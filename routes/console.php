<?php

use App\Console\Commands\RefreshHeatmapCells;

Schedule::command(RefreshHeatmapCells::class)->daily();
