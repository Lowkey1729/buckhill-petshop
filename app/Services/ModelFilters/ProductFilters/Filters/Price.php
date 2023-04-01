<?php

namespace App\Services\ModelFilters\ProductFilters\Filters;

use App\Services\ModelFilters\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class Price implements Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->where('price', $value);
    }
}
