<?php

namespace App\Services\ModelFilters\UserFilters\Filters;

use App\Services\Contracts\ModelFilter\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

final class Email implements Filter
{
    public static function apply(BuilderContract $builder, string $value): BuilderContract
    {
        return $builder->where('email', $value);
    }
}
