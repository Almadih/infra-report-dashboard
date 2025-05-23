<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = collect(['pending', 'under_review', 'verified', 'resolved']);

        $statuses->each(function ($status) {
            Status::factory()->create([
                'name' => $status,
            ]);
        });
    }
}
