<?php

namespace Si6\Base\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait Queryable
{
    protected $limit = 10;

    protected $maxLimit = 100;

    public function query(array $keys)
    {
        $results = [];

        $input = request()->query();

        foreach ($keys as $key) {
            [$key, $default] = $this->splitDefault($key);

            $value = data_get($input, $key, null);

            if ($value === null) {
                $value = $default;
            }

            $method = 'query' . Str::studly($key);

            if (method_exists($this, $method)) {
                $value = $this->{$method}($value);
            }

            Arr::set($results, $key, $value);
        }

        return $results;
    }

    public function queryLimit($value)
    {
        if ($value <= 0) {
            $value = $this->limit;
        }
        if ($value > $this->maxLimit) {
            $value = $this->maxLimit;
        }

        return (int)$value;
    }

    public function splitDefault($key)
    {
        $fields = explode(':', $key);

        return [$fields[0], $fields[1] ?? null];
    }
}
