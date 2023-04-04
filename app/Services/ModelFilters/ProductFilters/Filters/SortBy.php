<?php

namespace App\Services\ModelFilters\ProductFilters\Filters;

use App\Services\Contracts\ModelFilter\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Support\Facades\Schema;

final class SortBy implements Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        if (!in_array($value, Schema::getColumnListing('products'))) {
            return $builder;
        }

        return $builder->orderBy($value);
    }
}
