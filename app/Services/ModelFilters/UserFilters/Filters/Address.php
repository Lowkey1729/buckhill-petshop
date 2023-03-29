<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class Address implements \App\Services\ModelFilters\Filter
{
    /**
     * Filters based on the address of the user
     *
     * @param Builder<User> $builder
     * @param string $value
     * @return Builder<User>
     */
    public static function apply(Builder $builder, string $value): Builder
    {
        return $builder->where('address', 'LIKE', "%s{$value}%s");
    }
}
