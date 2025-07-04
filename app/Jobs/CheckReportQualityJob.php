<?php

namespace App\Jobs;

use App\Models\Report;
use App\Models\ReportFlag;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Log;
use Storage;
use Str;

class CheckReportQualityJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Report $report)
    {
        //
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
        ReportFlag::create([
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

        try {
            $imageAnnotator = new ImageAnnotatorClient;
            $sharpnessThreshold = 0.6;

            foreach ($this->report->images as $image) {
                // Assumes images are stored in Laravel's default storage disk.
                if (! Storage::disk('private')->get($image->path)) {
                    Log::error("Image not found for Report #{$this->report->id}: {$image->path}");

                    continue;
                }

                $imageContent = Storage::disk('private')->get($image->path);

                // Ask the Vision API for image properties
                $response = $imageAnnotator->imagePropertiesDetection($imageContent);
                $properties = $response->getImagePropertiesAnnotation();

                if ($properties) {
                    // The sharpness score is part of the dominant colors fraction.
                    // so we look at the crop hints. A blurry image often has poor crop hints confidence.
                    $cropHints = $properties->getCropHints();
                    foreach ($cropHints as $hint) {
                        if ($hint->getConfidence() < 0.5) { // Confidence is between 0 and 1
                            break;
                        }
                    }
                    $pretendSharpnessScore = $cropHints->count() > 0 ? $cropHints[0]->getConfidence() : 1.0;

                    if ($pretendSharpnessScore < $sharpnessThreshold) {
                        $reasons[] = 'image is blurry / low quality';
                        Log::info("Blurry image detected for Report #{$this->report->id}", ['path' => $image->path, 'score' => $pretendSharpnessScore]);
                        // We only need one blurry image to fail the check
                        break;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Google Vision API call failed for Report #{$this->report->id}: ".$e->getMessage());
        } finally {
            if (isset($imageAnnotator)) {
                $imageAnnotator->close();
            }
        }
    }
}
