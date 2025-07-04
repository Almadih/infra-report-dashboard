<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReportFlag>
 */
class ReportFlagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['duplicate', 'low_quality']);

        return [
            'type' => $type,
            'report_id' => Report::factory(),
            'duplicated_report_id' => $type == 'duplicate' ? Report::factory() : null,
            'auto_flagged' => $this->faker->boolean(),
            'confirmed_by_admin' => $this->faker->boolean(),
            'reason' => $this->faker->paragraph(),
        ];
    }
}
