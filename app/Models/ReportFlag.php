<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportFlag extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function duplicatedReport(): BelongsTo
    {
        return $this->belongsTo(Report::class, 'duplicated_report_id');
    }
}
