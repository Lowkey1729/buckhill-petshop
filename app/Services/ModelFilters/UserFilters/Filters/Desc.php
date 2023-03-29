<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class Desc implements \App\Services\ModelFilters\Filter
{
    /**
     * Filters based on the latest item in the model or the first item in the model
     *
     * @param Builder<User> $builder
     * @param string $value
     * @return Builder<User>
     */
    public static function apply(Builder $builder, string $value): Builder
    {
        if (! $value) {
            return $builder;
        }

        return $builder->latest();
    }
}
