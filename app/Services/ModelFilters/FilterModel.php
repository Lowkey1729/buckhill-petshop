<?php

namespace App\Services\ModelFilters;

use App\Services\Contracts\Filter;
use Illuminate\Contracts\Database\Eloquent\Builder as BuilderContract;
use Illuminate\Support\Str;

class FilterModel
{
    /**
     * The result of the builder or query is set here
     *
     * @param BuilderContract $query
     * @return BuilderContract
     */
    protected static function getResults(BuilderContract $query): BuilderContract
    {
        return $query->orderBy('created_at', 'ASC');
    }

    /**
     * After the namespace has been called, check if the class from
     *  the namespace exists truly then return the class if it exists.
     *
     * @param array $filters
     * @param BuilderContract $query
     * @param string $nameSpace
     * @return BuilderContract
     */
    protected static function applyDecoratorFromRequest(array $filters, BuilderContract $query, string $nameSpace): BuilderContract
    {
        foreach ($filters as $filterName => $value) {
            $decorator = self::createFilterDecorator($filterName, $nameSpace);
            if (self::isValidDecorator($decorator)) {
                /** @var Filter $decorator */
                $query = $decorator::apply($query, $value);
            }
        }

        return $query;
    }

    /**
     * return the namespace
     */
    protected static function createFilterDecorator(string $filterName, string $nameSpace): string
    {
        return $nameSpace . '\\Filters\\' . Str::studly($filterName);
    }

    /**
     * Checks if the class exists in the app
     */
    protected static function isValidDecorator(string $decorator): bool
    {
        return class_exists($decorator);
    }
}
