<?php

namespace Si6\Base\Criteria;

use Closure;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Si6\Base\Exceptions\MicroservicesException;
use Si6\Base\Http\Queryable;
use Si6\Base\Services\AuthService;

abstract class Criteria
{
    use Queryable;

    protected $table;

    protected $criteria;

    protected $param = [];

    protected $flatten = [];

    public function __construct(array $param = [])
    {
        $this->flatten = collect($this->criteria)->flatten()->toArray();
        $this->param   = $param ?: $this->query($this->flatten);
    }

    /**
     * @param  Builder  $query
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    public function applyQuery(Builder $query)
    {
        foreach ($this->param as $key => $value) {
            if (!$this->isRealParam($value) || !$this->isValidCriteria($key)) {
                continue;
            }
            $this->applyCriteria($query, $key, $value);
        }
        $this->queryUserCriteria($query);
    }

    protected function isRealParam($value)
    {
        if (is_array($value) && empty($value)) {
            return false;
        }

        return !is_null($value);
    }

    protected function isValidCriteria($field)
    {
        return in_array($field, $this->flatten);
    }

    protected function applyCriteria(Builder $query, $field, $value)
    {
        $method = 'criteria' . Str::studly($field);
        if (method_exists($this, $method)) {
            $this->{$method}($query, $value);

            return;
        }

        if ($this->isValidCriteriaField($field, 'filter')) {
            $value = is_array($value) ? $value : [$value];
            $query->whereIn("$this->table.$field", $value);

            return;
        }

        if ($this->isValidCriteriaField($field, 'search')) {
            if (is_string($value)) {
                $query->where("$this->table.$field", 'LIKE', "%$value%");
            }

            return;
        }
    }

    protected function isValidCriteriaField($field, $criteriaKey)
    {
        return !empty($this->criteria[$criteriaKey])
            && is_array($this->criteria[$criteriaKey])
            && in_array($field, $this->criteria[$criteriaKey]);
    }

    protected function parseDate($value, $format, Closure $callback)
    {
        try {
            $date = Carbon::createFromFormat($format, $value);
        } catch (\Exception $exception) {
            $date = null;
        }

        return $date ? $callback($date) : $date;
    }

    protected function parseStartOfDate($value, $format = 'Y-m-d')
    {
        return $this->parseDate($value, $format, function (Carbon $date) {
            return $date->startOfDay();
        });
    }

    protected function parseEndOfDate($value, $format = 'Y-m-d')
    {
        return $this->parseDate($value, $format, function (Carbon $date) {
            return $date->endOfDay();
        });
    }

    /**
     * @param  Builder  $query
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function queryUserCriteria(Builder $query)
    {
        if (empty($this->criteria['user'])) {
            return;
        }

        $param = collect($this->param);
        $param->each(function ($value, $key) use ($param) {
            if (!in_array($key, $this->criteria['user']) || is_null($value)) {
                $param->forget($key);
            }
        });

        if ($param->isEmpty()) {
            return;
        }

        /** @var AuthService $authService */
        $authService = app(AuthService::class)->getInstance();

        $users = $authService->getUsers($param->toArray());

        $query->whereIn("$this->table.user_id", collect($users)->pluck('id'));
    }
}
