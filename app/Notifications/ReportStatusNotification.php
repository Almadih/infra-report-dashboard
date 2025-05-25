<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Str;

class ReportStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Report $report)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via()
    {
        return ['fcm', 'database'];
    }

    // optional: you can use according to your requirement.
    public function toFCM()
    {
        $status = Str::headline($this->report->status->name);

        return [
            'title' => 'Report Status updated',
            'body' => "Report #{$this->report->id} status has been updated to {$status}",
        ];
    }

    public function toData()
    {
        return [

            'report_id' => $this->report->id,

        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = Str::headline($this->report->status->name);

        return [
            'title' => 'Report Status updated',
            'body' => "Report #{$this->report->id} status has been updated to {$status}",
            'data' => [
                'report_id' => $this->report->id,
            ],
        ];
    }
}
