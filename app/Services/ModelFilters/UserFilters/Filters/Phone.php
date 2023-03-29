<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class Phone implements \App\Services\ModelFilters\Filter
{
    /**
     * Filter based on the phone number of the user
     *
     * @param Builder<User> $builder
     * @param string $value
     * @return Builder<User>
     */
    public static function apply(Builder $builder, string $value): Builder
    {
        return $builder->where('phone_number', $value);
    }
}
