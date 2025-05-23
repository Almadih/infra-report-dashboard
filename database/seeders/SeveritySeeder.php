<?php

namespace Database\Seeders;

use App\Models\Severity;
use Illuminate\Database\Seeder;

class SeveritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $severities = collect(['low', 'medium', 'high', 'critical']);
        $severities->each(function ($severity) {
            Severity::factory()->create([
                'name' => $severity,
            ]);
        });
    }
}
