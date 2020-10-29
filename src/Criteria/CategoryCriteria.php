<?php

namespace Si6\Base\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Si6\Base\Category;

class CategoryCriteria extends Criteria
{
    protected $model = Category::class;

    protected $criteria = [
        'filter' => [
            'type',
            'status',
        ],
        'name',
        'date' => [
            'created_at',
            'updated_at',
        ]
    ];

    /**
     * @param $query
     * @param $value
     */
    protected function criteriaName($query, $value)
    {
        /** @var Builder $query */
        $query->whereHas('translations', function ($query) use ($value) {
            $query->where('name', 'LIKE', "%$value%");
        });
    }
}
