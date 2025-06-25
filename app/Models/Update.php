<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Update extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the report that owns the Update
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}
