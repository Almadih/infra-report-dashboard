<?php

namespace Database\Seeders;

use App\Models\DamageType;
use Illuminate\Database\Seeder;

class DamageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $damageTypes = collect(['School', 'Road', 'Power Line', 'Water Line', 'Mosque', 'Other']);

        $damageTypes->each(function ($damageType) {

            DamageType::factory()->create(['name' => $damageType]);

        });
    }
}
