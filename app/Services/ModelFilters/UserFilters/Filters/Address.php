<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class Address implements \App\Services\ModelFilters\Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->where('address', 'LIKE', "%s{$value}%s");
    }
}
