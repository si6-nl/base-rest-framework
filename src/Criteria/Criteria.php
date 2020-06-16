<?php

namespace Si6\Base\Criteria;

use Closure;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Si6\Base\Exceptions\CriteriaNotHasModel;
use Si6\Base\Http\Queryable;
use Si6\Base\Model;
use Si6\Base\Services\AuthService;
use Si6\Base\Services\UserService;

abstract class Criteria
{
    use Queryable;

    protected $table;

    protected $tablePrefix;

    protected $model;

    protected $criteria;

    protected $param = [];

    protected $flatten = [];

    /**
     * Criteria constructor.
     *
     * @param  array  $param
     * @throws CriteriaNotHasModel
     */
    public function __construct(array $param = [])
    {
        $model = app($this->model);
        if (!($model instanceof Model)) {
            throw new CriteriaNotHasModel();
        }
        $this->tablePrefix = DB::getTablePrefix();
        $this->table       = $model->getTable();
        $this->flatten     = collect($this->criteria)->flatten()->toArray();
        $this->param       = $param ?: $this->query($this->flatten);
    }

    protected function getFullTableName()
    {
        return $this->tablePrefix . $this->table;
    }

    public function applyQuery(Builder $query)
    {
        foreach ($this->param as $key => $value) {
            if (!$this->isValidCriteria($key)) {
                continue;
            }
            $this->applyCriteria($query, $key, $value);
        }
        $this->queryUserCriteria($query);
        $this->queryProfileCriteria($query);
    }

    protected function queryUserCriteria(Builder $query)
    {
        $this->queryExternalCriteria($query, 'user', function (Builder $query, Collection $param) {
            /** @var AuthService $authService */
            $authService = app(AuthService::class)->getInstance();

            $users = $authService->getUsers($param->toArray());

            $query->whereIn("$this->table.user_id", collect($users)->pluck('id'));
        });
    }

    protected function queryProfileCriteria(Builder $query)
    {
        $this->queryExternalCriteria($query, 'profile', function (Builder $query, Collection $param) {
            /** @var UserService $userService */
            $userService = app(UserService::class)->getInstance();

            $users = $userService->getProfiles($param->toArray());

            $query->whereIn("$this->table.user_id", collect($users)->pluck('user_id'));
        });
    }

    protected function queryExternalCriteria(Builder $query, $external, Closure $callback)
    {
        if (empty($this->criteria[$external])) {
            return;
        }

        $param = collect($this->param);

        $param->each(function ($value, $key) use ($param, $external) {
            if (!in_array($key, $this->criteria[$external]) || is_null($value)) {
                $param->forget($key);
            }
        });

        if ($param->isEmpty()) {
            return;
        }

        $callback($query, $param);
    }

    protected function isValidCriteria($field)
    {
        $from = Str::replaceLast('_from', "", $field);
        $to = Str::replaceLast('_to', "", $field);
        if ((in_array($from, $this->flatten) || in_array($to, $this->flatten))
            && !is_null($this->param[$field])
        ) {
            return true;
        }

        return in_array($field, $this->flatten) && !is_null($this->param[$field]);
    }

    protected function applyCriteria(Builder $query, $field, $value)
    {
        $method = 'criteria' . Str::studly($field);
        if (method_exists($this, $method)) {
            $this->{$method}($query, $value);

            return;
        }

        if ($this->isValidCriteriaField($field, 'filter')) {
            if (is_array($value)) {
                $value = array_filter($value, function ($var) {
                    return $var !== null;
                });
                if (empty($value)) {
                    return;
                }
            }

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

        if ($this->isValidCriteriaField(Str::replaceLast('_from', "", $field), 'date')) {
            $this->queryDateFrom($query, Str::replaceLast('_from', "", $field), $value);

            return;
        }

        if ($this->isValidCriteriaField(Str::replaceLast('_to', "", $field), 'date')) {
            $this->queryDateTo($query, Str::replaceLast('_to', "", $field), $value);

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
            $date = Carbon::createFromFormat($format, $value, config('time.user_timezone'));
        } catch (Exception $exception) {
            $date = null;
        }

        return $date ? $callback($date) : $date;
    }

    protected function parseStartOfDate($value, $format = 'Y-m-d')
    {
        return $this->parseDate($value, $format, function (Carbon $date) {
            return $date->startOfDay()->setTimezone('UTC');
        });
    }

    protected function parseEndOfDate($value, $format = 'Y-m-d')
    {
        return $this->parseDate($value, $format, function (Carbon $date) {
            return $date->endOfDay()->setTimezone('UTC');
        });
    }

    protected function queryCriteriaDate(Builder $query, $field, $operator, $time)
    {
        if ($time) {
            $query->where("$this->table.$field", $operator, $time);
        }
    }

    protected function queryDateFrom(Builder $query, $field, $value)
    {
        $time = $this->parseStartOfDate($value);
        $this->queryCriteriaDate($query, $field, '>=', $time);
    }

    protected function queryDateTo(Builder $query, $field, $value)
    {
        $time = $this->parseEndOfDate($value);
        $this->queryCriteriaDate($query, $field, '<=', $time);
    }

    protected function getTimeOffset()
    {
        $appOffset    = now()->format('P');
        $userTimezone = config('time.user_timezone');
        $userOffset   = now($userTimezone)->format('P');

        return [$appOffset, $userOffset];
    }

    protected function queryCriteriaYear($query, $field, $value)
    {
        [$appOffset, $userOffset] = $this->getTimeOffset();
        $query->whereYear(DB::raw("CONVERT_TZ(". $this->getFullTableName() . ".$field, '$appOffset', '$userOffset')"), $value);
    }

    protected function queryCriteriaMonth($query, $field, $value)
    {
        [$appOffset, $userOffset] = $this->getTimeOffset();
        $query->whereMonth(DB::raw("CONVERT_TZ(". $this->getFullTableName() . ".$field, '$appOffset', '$userOffset')"), $value);
    }
}
