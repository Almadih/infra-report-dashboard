<?php

namespace Database\Seeders;

use App\Models\ReportFlag;
use Illuminate\Database\Seeder;

class ReportFlagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReportFlag::factory(10)->create([]);
    }
}
