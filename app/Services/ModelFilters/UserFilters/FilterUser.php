<?php

namespace App\Services\ModelFilters\UserFilters;

use App\Models\User;
use App\Services\ModelFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @template TKey of array-key
 * @template TModel
 * @template TItem
 * */
final class FilterUser
{
    /**
     * The first argument passed is from the request fields.
     *
     * The filter files generated should be based on the request field passed here
     *
     * @param array $filters
     * @return Builder<User>
     */
    public static function apply(array $filters): Builder
    {
        $query = self::applyDecoratorFromRequest($filters, (
        new User())->newQuery());

        return self::getResults($query);
    }

    /**
     * The result of the builder or query is set here
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    protected static function getResults(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'ASC');
    }

    /**
     * After the namespace has been called, check if the class from
     *  the namespace exists truly then return the class if it exists.
     *
     * @param array $filters
     * @param Builder<User> $query
     * @return Builder<User>
     */
    protected static function applyDecoratorFromRequest(array $filters, Builder $query): Builder
    {
        foreach ($filters as $filterName => $value) {
            $decorator = self::createFilterDecorator($filterName);
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
    protected static function createFilterDecorator(string $filterName): string
    {
        return __NAMESPACE__.'\\Filters\\'.Str::studly($filterName);
    }

    /**
     * Checks if the class exists in the app
     */
    protected static function isValidDecorator(string $decorator): bool
    {
        return class_exists($decorator);
    }
}
