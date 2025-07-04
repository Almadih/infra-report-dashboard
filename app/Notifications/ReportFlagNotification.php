<?php

namespace App\Notifications;

use App\Models\ReportFlag;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Str;

class ReportFlagNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private string $flagType;

    private string $reportDamageType;

    public function __construct(private ReportFlag $flag)
    {
        $this->flagType = Str::headline($this->flag->type);
        $this->reportDamageType = $flag->report->damageType->name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'fcm'];
    }

    public function toFCM()
    {

        return [
            'title' => 'Your Report was flagged',
            'body' => "Report {$this->reportDamageType} has been flagged as {$this->flagType}",
        ];
    }

    public function toData()
    {
        return [

            'report_id' => $this->flag->report_id,

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
            'title' => 'Your Report was flagged',
            'body' => "Report {$this->reportDamageType} has been flagged as {$this->flagType}",
            'data' => [
                'report_id' => $this->flag->report_id,
            ],
        ];
    }
}
