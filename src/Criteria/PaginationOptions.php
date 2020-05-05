<?php

namespace Si6\Base\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Si6\Base\Http\Queryable;

class PaginationOptions
{
    use Queryable;

    protected $pagination;

    public function __construct($limit = null, $page = null)
    {
        $param = $this->query(['limit:25', 'page:1']);

        $this->pagination['limit'] = $limit ?: $param['limit'];
        $this->pagination['page']  = $page ?: $param['page'];
    }

    public function applyQuery(Builder $query)
    {
        return $query->paginate((int)$this->pagination['limit'], ['*'], 'page', (int)$this->pagination['page']);
    }
}
