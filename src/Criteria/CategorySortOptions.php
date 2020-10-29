<?php

namespace Si6\Base\Criteria;

class CategorySortOptions extends SortOptions
{
    protected $sorts = [
        'id',
        'updated_at',
    ];

    protected $default = [
        ['id', 'desc'],
    ];
}
