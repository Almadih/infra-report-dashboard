<?php

namespace App\Jobs;

use App\Models\Report;
use App\Models\ReportFlag;
use App\Notifications\ReportFlagNotification;
use App\Services\ReputationService;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Database\PostgisFunctions\ST;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Log;

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
        try {

            $radius = 100; // meters
            $centerPoint = Point::makeGeodetic($this->report->location->getLatitude(), $this->report->location->getLongitude());

            $reports = Report::where(ST::dWithinGeography('location', $centerPoint, $radius), true)->where('damage_type_id', $this->report->damage_type_id)->whereHas('flags', function ($query) {
                return $query->where('type', '!=', 'duplicate');
            })->get();

            if ($reports->isEmpty()) {
                return;
            }

            foreach ($reports as $report) {
                $flag = ReportFlag::create([
                    'report_id' => $this->report->id,
                    'duplicated_report_id' => $report->id,
                    'type' => 'duplicate',
                    'reason' => "Report is a duplicate of Report #{$report->id}",
                ]);
                Log::info("Report #{$this->report->id} flagged as DUPLICATE to Report #{$report->id}");
                $this->report->user->notify(new ReportFlagNotification($flag));
            }

            ReputationService::addReputationHistory($report, ReputationService::TYPE_DUPLICATE);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
