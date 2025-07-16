<?php

namespace App\Models;

use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Report query()
 *
 * @mixin \Eloquent
 */
class Report extends Model
{
    use HasFactory,HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'location' => Point::class,
    ];

    /**
     * Get the status associated with the Report
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function severity(): BelongsTo
    {
        return $this->belongsTo(Severity::class);
    }

    public function damageType(): BelongsTo
    {
        return $this->belongsTo(DamageType::class);
    }

    /**
     * Get the user that owns the Report
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the images for the Report
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get all of the updates for the Report
     */
    public function updates(): HasMany
    {
        return $this->hasMany(Update::class)->latest();
    }

    /**
     * Get all of the reputationHistory for the Report
     */
    public function reputationHistory(): HasMany
    {
        return $this->hasMany(ReputationHistory::class);
    }

    /**
     * Get all of the flags for the Report
     */
    public function flags(): HasMany
    {
        return $this->hasMany(ReportFlag::class)->latest()->where('confirmed_by_admin', true);
    }
}
