<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FilterRelationIn implements Filter
{
    protected string $relation;

    protected string $column;

    public function __construct(string $relation, string $column)
    {
        $this->relation = $relation;
        $this->column = $column;
    }

    public function __invoke(Builder $query, $value, string $property): void
    {
        $values = is_array($value) ? $value : explode(',', $value);

        $query->whereHas($this->relation, function ($q) use ($values) {
            $q->whereIn($this->column, $values);
        });
    }
}
