<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportUpdatesNotification extends Notification
{
    use Queueable;

    private string $damageType;

    public function __construct(public Report $report)
    {
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
            'title' => 'Report got new updated',
            'body' => "Report {$this->damageType} has new updates from admin",
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
            'title' => 'Report got new updated',
            'body' => "Report {$this->damageType} has new updates from admin",
            'data' => [
                'report_id' => $this->report->id,
            ],
        ];
    }
}
