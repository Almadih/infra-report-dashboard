<?php

namespace Database\Seeders;

use App\Models\DamageType;
use App\Models\Report;
use App\Models\Severity;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Clickbar\Magellan\Data\Geometries\Point;
use DB;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class ReportSeeder extends Seeder
{
    protected $faker;

    protected string $placeholderPath;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return \Faker\Generator
     */
    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::now()->subMonth(); // e.g., 1 month ago
        $endDate = Carbon::now();             // e.g., up to today
        $count = 100; // total reports
        $chunkSize = 100; // insert 100 at a time

        // Cache IDs to avoid repeated DB calls
        $severityIds = Severity::pluck('id')->all();
        $statusIds = Status::pluck('id')->all();
        $damageTypeIds = DamageType::pluck('id')->all();
        $userIds = User::where('is_anonymous', true)->pluck('id')->all();
        $this->generatePlaceholder();

        for ($offset = 0; $offset < $count; $offset += $chunkSize) {
            $reports = [];
            $reportIds = [];

            for ($i = 0; $i < $chunkSize; $i++) {
                $uuid = (string) Str::uuid();
                $reportIds[] = $uuid;
                $randomPoint = $this->randomGeo(30.037059, 31.225884, 10000);
                $date = $this->faker->dateTimeBetween($startDate, $endDate);
                $reports[] = [
                    'id' => $uuid,
                    'severity_id' => $severityIds[array_rand($severityIds)],
                    'status_id' => $statusIds[array_rand($statusIds)],
                    'damage_type_id' => $damageTypeIds[array_rand($damageTypeIds)],
                    'user_id' => $userIds[array_rand($userIds)],
                    'location' => Point::makeGeodetic($randomPoint['longitude'], $randomPoint['latitude']),
                    'address' => $this->faker->address(),
                    'description' => $this->faker->paragraph(),
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
            }

            // Insert all reports in one query
            DB::table('reports')->insert($reports);
            echo "Inserted $chunkSize reports (UUIDs)".PHP_EOL;

            $this->bulkAttachImages($reportIds);

            echo "image attached $chunkSize reports (UUIDs)".PHP_EOL;
        }
    }

    protected function generatePlaceholder(): void
    {
        $image = Image::create(800, 600)->fill('#000')
            ->text('Placeholder', 400, 300, function ($font) {
                $font->size(70);
                $font->color('#fff');
                $font->align('center');
                $font->valign('middle');
            });

        $this->placeholderPath = 'reports/placeholder.jpg';
        Storage::disk('private')->put($this->placeholderPath, (string) $image->encodeByExtension('jpg', 80));
    }

    /**
     * Copy placeholder image for each report and insert image records
     */
    protected function bulkAttachImages(array $reportIds): void
    {
        $images = [];

        foreach ($reportIds as $reportId) {
            $reportPath = "reports/{$reportId}";
            for ($ii = 1; $ii <= 2; $ii++) {
                $imageName = "image-{$ii}.jpg";
                $storedPath = "{$reportPath}/{$imageName}";

                // Just copy the placeholder instead of generating a new one
                Storage::disk('private')->copy($this->placeholderPath, $storedPath);

                $images[] = [
                    'report_id' => $reportId,
                    'path' => $storedPath,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert all image records in one query
        DB::table('images')->insert($images);
    }

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
}
