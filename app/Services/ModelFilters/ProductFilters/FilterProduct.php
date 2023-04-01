<?php

namespace App\Services\ModelFilters\ProductFilters;

use App\Models\Product;
use App\Services\ModelFilters\FilterModel;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;

class FilterProduct extends FilterModel
{
    /**
     * The first argument passed is from the request fields.
     *
     * The filter files generated should be based on the request field passed here
     *
     * @param array $filters
     * @return BuilderContract
     */
    public static function apply(array $filters): BuilderContract
    {
        $query = self::applyDecoratorFromRequest(
            $filters,
            (new Product())->newQuery()->with(['category']),
            __NAMESPACE__
        );

        return self::getResults($query);
    }
}
