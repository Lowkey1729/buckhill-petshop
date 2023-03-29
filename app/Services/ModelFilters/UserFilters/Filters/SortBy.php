<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Models\User;
use App\Services\ModelFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class SortBy implements Filter
{
    /**
     * Filters based on the column selected.
     *
     * @param Builder<User> $builder
     * @param string $value
     * @return Builder<User>
     */
    public static function apply(Builder $builder, string $value): Builder
    {
        if (! in_array($value, Schema::getColumnListing('users'))) {
            return $builder;
        }

        return $builder->orderBy($value);
    }
}
