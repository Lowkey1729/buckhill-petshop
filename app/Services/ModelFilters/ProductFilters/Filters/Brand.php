<?php

namespace App\Services\ModelFilters\ProductFilters\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use App\Services\ModelFilters\Filter;

final class Brand implements Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->whereJsonContains('category_uuid', $value);
    }
}
