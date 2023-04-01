<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Services\Contracts\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class FirstName implements Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->where('first_name', 'LIKE', "%s{$value}%s");
    }
}
