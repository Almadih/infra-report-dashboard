<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class DateRangeFilter implements Filter
{
    protected string $column;

    public function __construct(string $column)
    {
        $this->column = $column;
    }

    public function __invoke(Builder $query, $value, string $property): void
    {
        // Expecting value like: 2025-01-01,2025-01-31
        $dates = is_array($value) ? $value : explode(',', $value);

        if (count($dates) === 2) {
            [$start, $end] = $dates;

            if (! empty($start)) {
                $query->whereDate($this->column, '>=', $start);
            }

            if (! empty($end)) {
                $query->whereDate($this->column, '<=', $end);
            }
        }
    }
}
