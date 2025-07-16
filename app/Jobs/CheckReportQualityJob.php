<?php

namespace App\Jobs;

use App\Models\Report;
use App\Models\ReportFlag;
use App\Services\ImageQualityService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Log;
use Storage;
use Str;

class CheckReportQualityJob implements ShouldQueue
{
    use Queueable;

    private ImageQualityService $imageService;

    /**
     * Create a new job instance.
     */
    public function __construct(private Report $report)
    {
        $this->imageService = new ImageQualityService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Log::info("Starting quality check for Report #{$this->report->id}");

        $reasons = [];

        // 1. Check Description Quality
        $this->checkDescription($reasons);

        // 2. Check Image Quality
        $this->checkImages($reasons);

        // 3. Update the report if it's low quality
        if (empty($reasons)) {
            Log::info("Report #{$this->report->id} passed quality checks.");

            return;
        }
        $flag = ReportFlag::create([
            'report_id' => $this->report->id,
            'type' => 'low_quality',
            'reason' => implode(', ', $reasons),
        ]);
        Log::warning("Report #{$this->report->id} flagged as LOW QUALITY.", ['reasons' => $reasons]);
    }

    /**
     * Analyzes the report description for quality issues.
     */
    private function checkDescription(array &$reasons): void
    {
        $description = $this->report->description;
        $wordCount = Str::wordCount($description);
        $minWords = 4;

        // Check 2: Nonsense (e.g., just random letters, no spaces)
        if (! preg_match('/\s/', $description) && strlen($description) > 20 || $wordCount < $minWords) {
            $reasons[] = 'description is nonsense / too short';
        }
    }

    /**
     * Analyzes the report images for blurriness.
     */
    private function checkImages(array &$reasons): void
    {
        if ($this->report->images->isEmpty()) {
            return; // No images to check
        }

        foreach ($this->report->images as $image) {
            // Assumes images are stored in Laravel's default storage disk.
            if (! Storage::disk('private')->get($image->path)) {
                Log::error("Image not found for Report #{$this->report->id}: {$image->path}");

                continue;
            }

            if ($this->imageService->isBlurry($image)) {
                $reasons[] = "image #{$image->id} is blurry";
            }

            if ($this->imageService->isLowQuality($image)) {
                $reasons[] = "image #{$image->id} is low quality";
            }
        }
    }
}
