<?php

namespace Si6\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Si6\Base\Criteria\Criteria;
use Si6\Base\Criteria\PaginationOptions;
use Si6\Base\Criteria\SortOptions;

class BaseRepository
{
    /**
     * @param string $model
     * @param Criteria|null $criteria
     * @param SortOptions|null $sort
     * @return Builder
     */
    protected function queryCriteria(string $model, Criteria $criteria = null, SortOptions $sort = null)
    {
        $call = $model . '::query';

        /** @var Builder $query */
        $query = $call();

        if (!$criteria) {
            $criteria = app(Criteria::class);
        }

        $criteria->applyQuery($query);

        if (!$sort) {
            $sort = app(SortOptions::class);
        }

        $sort->applyQuery($query);

        return $query;
    }

    /**
     * @param Builder $query
     * @param PaginationOptions|null $pagination
     * @return LengthAwarePaginator
     */
    protected function pagination(Builder $query, PaginationOptions $pagination = null)
    {
        if (!$pagination) {
            $pagination = app(PaginationOptions::class);
        }

        return $pagination->applyQuery($query);
    }
}
