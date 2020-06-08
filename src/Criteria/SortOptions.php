<?php

namespace Si6\Base\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SortOptions
{
    /** @var array $sorts */
    protected $sorts = [
        'id',
        'updated_at',
    ];

    /** @var array $param */
    protected $param = [];

    protected $default = [
        ['updated_at', 'desc'],
        ['id', 'desc'],
    ];

    public function __construct(array $param = [])
    {
        $this->setParam($param);
    }

    protected function setParam(array $param)
    {
        if (empty($param['sort']) || !is_string($param['sort'])) {
            $this->param = $this->default;

            return;
        }

        $sorts = explode(',', $param['sort']);

        foreach ($sorts as $string) {
            $first = Str::substr($string, 0, 1);
            if ($first === '-') {
                $field = Str::substr($string, 1);
                $order = 'desc';
            } else {
                $field = $string;
                $order = 'asc';
            }

            if (in_array($field, $this->sorts)) {
                $this->param[] = [$field, $order];
            }
        }

        // set default if param still empty
        if (empty($this->param)) {
            $this->param = $this->default;

            return;
        }
    }

    public function applyQuery(Builder $query)
    {
        foreach ($this->param as $sort) {
            $query->orderBy($sort[0], $sort[1]);
        }

        return $query;
    }
}
