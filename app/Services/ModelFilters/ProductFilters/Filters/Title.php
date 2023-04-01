<?php

namespace App\Services\ModelFilters\ProductFilters\Filters;

use App\Services\Contracts\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class Title implements Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->where('title', 'LIKE', "%{$value}%");
    }
}
