<?php

namespace App\Services\ModelFilters\ProductFilters\Filters;

use App\Services\Contracts\ModelFilter\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class Brand implements Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->whereJsonContains('category_uuid', $value);
    }
}
