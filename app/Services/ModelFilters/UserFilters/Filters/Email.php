<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Models\User;
use App\Services\ModelFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class Email implements Filter
{
    /**
     * Filter based on the email of the user
     *
     * @param Builder<User> $builder
     * @param string $value
     * @return Builder<User>
     */
    public static function apply(Builder $builder, string $value): Builder
    {
        return $builder->where('email', $value);
    }
}
