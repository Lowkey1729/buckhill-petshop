<?php

namespace App\Services\ModelFilters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder<User> $builder
     * @param string $value
     * @return Builder<User>
     */
    public static function apply(Builder $builder, string $value): Builder;
}
