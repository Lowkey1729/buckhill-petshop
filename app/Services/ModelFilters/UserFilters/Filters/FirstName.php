<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Models\User;
use App\Services\ModelFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

final class FirstName implements Filter
{
    /**
     * Filters  based on the first name of the user
     *
     * @param Builder<User> $builder
     * @param string $value
     * @return Builder<User>
     */
    public static function apply(Builder $builder, string $value): Builder
    {
        return $builder->where('first_name', 'LIKE', "%s$value%s");
    }
}
