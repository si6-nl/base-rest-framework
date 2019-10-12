<?php

namespace Si6\Base;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait MultipleUpdatable
{
    public function multipleUpdate($attributes, $keys = [])
    {
        list($attributes, $keys) = $this->prepareParamUpdating($attributes, $keys);

        if (!$attributes) {
            return 0;
        }

        list($table, $sets, $where, $bindings) = $this->prepareQueryUpdate($attributes, $keys);

        $query = /** @lang text */
            "UPDATE `{$table}` SET {$sets} WHERE ({$where})";

        return DB::update($query, $bindings);
    }

    protected function prepareParamUpdating($attributes, $keys)
    {
        if ($attributes instanceof Collection) {
            $attributes = $attributes->toArray();
        }

        if (!$keys) {
            $keys = [$this->getKeyName()];
        }
        if (!$keys instanceof Collection) {
            $keys = is_array($keys) ? collect($keys) : collect([$keys]);
        }

        return [$attributes, $keys];
    }

    protected function prepareQueryUpdate(array $attributes, Collection $keys)
    {
        $table = DB::getTablePrefix() . $this->getTable();

        list($cases, $where) = $this->analyzeAttributes($attributes, $keys);

        list($sets, $bindings) = $this->analyzeCases($cases);

        $sets              = implode(',', $sets);
        $where['query']    = implode(' OR ', $where['query']);
        $where['bindings'] = collect($where['bindings'])->flatten(1)->toArray();
        $bindings          = array_merge($bindings, $where['bindings']);

        return [$table, $sets, $where['query'], $bindings];
    }

    protected function emptyAttribute($attribute, $keys)
    {
        foreach ($keys as $key) {
            if (!isset($attribute[$key])) {
                return true;
            }
        }

        return false;
    }

    protected function analyzeAttributes(array $attributes, Collection $keys)
    {
        $conditions = $this->makeConditions($keys);

        $cases = $where = [
            'query'    => [],
            'bindings' => [],
        ];

        foreach ($attributes as $index => $attribute) {
            if ($this->emptyAttribute($attribute, $keys)) {
                continue;
            }

            foreach ($attribute as $field => $value) {
                if ($keys->contains($field)) {
                    continue;
                }

                $cases['query'][$field][]   = "WHEN ({$conditions}) THEN ?";
                $cases['bindings'][$field][] = $this->getValueFromAttribute($attribute, $keys)->merge([$value]);
            }

            $where['query'][]    = "({$conditions})";
            $where['bindings'][] = $this->getValueFromAttribute($attribute, $keys)->toArray();
        }

        return [$cases, $where];
    }

    protected function makeConditions(Collection $keys)
    {
        return $keys->map(function ($key) {
            return "`{$key}` = ?";
        })->implode(' AND ');
    }

    protected function getValueFromAttribute(array $attribute, Collection $keys)
    {
        return $keys->map(function ($key) use ($attribute) {
            return $attribute[$key];
        });
    }

    protected function analyzeCases(array $cases)
    {
        $sets = $bindings = [];

        foreach ($cases['query'] as $field => $case) {
            $case   = implode(' ', $case);
            $sets[] = "`{$field}` = CASE {$case} END";
            foreach ($cases['bindings'][$field] as $values) {
                foreach ($values as $value) {
                    $bindings[] = $value;
                }
            }
        }

        $sets[]     = "`updated_at` = ?";
        $bindings[] = Carbon::now();

        return [$sets, $bindings];
    }
}
