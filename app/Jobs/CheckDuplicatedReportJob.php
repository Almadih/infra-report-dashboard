<?php

namespace App\Jobs;

use App\Models\Report;
use App\Models\ReportFlag;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Database\PostgisFunctions\ST;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CheckDuplicatedReportJob implements ShouldQueue
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
        $radius = 100; // meters
        $centerPoint = Point::makeGeodetic($this->report->location->getLatitude(), $this->report->location->getLongitude());

        $reports = Report::where(ST::dWithinGeography('location', $centerPoint, $radius))->whereHas('damageType', function ($query) {
            $query->where('id', $this->report->damage_type_id);
        })->get();

        foreach ($reports as $report) {
            $descriptionSimilarity = $this->getTextSimilarity($this->report->description, $report->description);

            if ($descriptionSimilarity > 0.8) {
                ReportFlag::create([
                    'report_id' => $this->report->id,
                    'duplicated_report_id' => $report->id,
                    'type' => 'duplicate',
                ]);

            }
        }
    }

    private function getTextSimilarity($text1, $text2)
    {
        //
    }
}
