<?php

namespace App\Services\ModelFilters;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param BuilderContract $builder
     * @param string $value
     * @return BuilderContract
     */
    public static function apply(BuilderContract $builder, string $value): BuilderContract;
}
