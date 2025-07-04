<?php

namespace Database\Seeders;

use App\Models\DamageType;
use App\Models\Report;
use App\Models\Severity;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i <= 1; $i++) {
            error_log($i);
            $report = Report::factory()->create([
                'severity_id' => Severity::inRandomOrder()->first()->id,
                'status_id' => Status::inRandomOrder()->first()->id,
                'damage_type_id' => DamageType::inRandomOrder()->first()->id,
            ]);
            $reportPath = "reports/{$report->id}";
            for ($ii = 1; $ii <= 2; $ii++) {
                $imageName = "image-{$ii}.jpg";

                // Create dummy image
                $image = Image::create(800, 600)->fill('#000')
                    ->text("Report {$report->id} - Image {$ii}", 400, 300, function ($font) {
                        $font->size(70);
                        $font->color('fff');
                    });

                // Save to temp file and store in private disk
                $tempPath = storage_path("app/temp-{$imageName}");
                $image->save($tempPath);

                $storedPath = Storage::disk('private')->putFileAs($reportPath, new File($tempPath), $imageName);

                // Delete temp file
                unlink($tempPath);

                // Create related Image record
                $report->images()->create([
                    'path' => $storedPath,
                ]);
            }
        }

    }
}
