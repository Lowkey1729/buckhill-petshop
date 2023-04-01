<?php

namespace App\Services\Contracts;

use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

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
