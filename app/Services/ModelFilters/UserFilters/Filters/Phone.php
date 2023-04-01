<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class Phone implements \App\Services\Contracts\Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->where('phone_number', $value);
    }
}
