<?php

namespace Database\Factories;

use App\Models\DamageType;
use App\Models\Severity;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    public function randomGeo($latitude, $longitude, $radius)
    {
        $y0 = $latitude;
        $x0 = $longitude;
        $rd = $radius / 111300; // about 111300 meters in one degree

        $u = mt_rand() / mt_getrandmax();
        $v = mt_rand() / mt_getrandmax();

        $w = $rd * sqrt($u);
        $t = 2 * pi() * $v;
        $x = $w * cos($t);
        $y = $w * sin($t);

        $newLat = $y + $y0;
        $newLng = $x + $x0;

        return [
            'latitude' => $newLat,
            'longitude' => $newLng,
        ];
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomPoint = $this->randomGeo(29.984851, 30.817895, 100000);
        $startDate = Carbon::now()->subMonth(); // e.g., 1 month ago
        $endDate = Carbon::now();             // e.g., up to today

        // Generate a random date within that period
        $randomDate = $this->faker->dateTimeBetween($startDate, $endDate);

        return [
            'severity_id' => Severity::factory(),
            'status_id' => Status::factory(),
            'damage_type_id' => DamageType::factory(),
            'user_id' => User::factory(),
            'address' => $this->faker->address(),
            'description' => $this->faker->paragraph(),
            'location' => Point::makeGeodetic($randomPoint['longitude'], $randomPoint['latitude']),
            'city' => fake()->city(),
            'created_at' => $randomDate,

        ];
    }
}
