<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReportStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private string $status;

    private string $damageType;

    public function __construct(public Report $report)
    {
        $this->status = $report->status->name;
        $this->damageType = $report->damageType->name;
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

        return [
            'title' => 'Report Status updated',
            'body' => "Report {$this->damageType} status has been updated to {$this->status}",
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

        return [
            'title' => 'Report Status updated',
            'body' => "Report {$this->damageType} status has been updated to {$this->status}",
            'data' => [
                'report_id' => $this->report->id,
            ],
        ];
    }
}
