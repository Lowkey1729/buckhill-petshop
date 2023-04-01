<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class Desc implements \App\Services\Contracts\Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        if (!$value) {
            return $builder;
        }

        return $builder->latest();
    }
}
