<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReputationHistory extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the user that owns the ReputationHistory
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the report that owns the ReputationHistory
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
