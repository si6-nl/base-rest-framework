<?php

namespace Si6\Base;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait Importable
{
    protected function import($attributes, $keys = [])
    {
        /**
         * @var Collection $attributes
         * @var Collection $keys
         */
        [$attributes, $keys] = $this->prepareParamImporting($attributes, $keys);

        /** @var Collection $exists */
        $exists = $this->getExists($attributes, $keys);

        /**
         * @var Collection $updateAttributes
         * @var Collection $insertAttributes
         */
        [$updateAttributes, $insertAttributes] = $this->partitionUpdateInsert($attributes, $exists, $keys);

        $this->multipleUpdate($updateAttributes, $keys);

        $this->insertAttributes($insertAttributes);

        return [
            'update' => $updateAttributes->count(),
            'insert' => $insertAttributes->count(),
        ];
    }

    /**
     * @param $attributes
     * @param $keys
     * @return array
     */
    protected function prepareParamImporting($attributes, $keys)
    {
        if (!$attributes instanceof Collection) {
            $attributes = collect($attributes);
        }

        if (!$keys) {
            $keys = [$this->getKeyName()];
        }
        if (!$keys instanceof Collection) {
            $keys = is_array($keys) ? collect($keys) : collect([$keys]);
        }

        return [$attributes, $keys];
    }

    /**
     * @param Collection $attributes
     * @param Collection $keys
     * @return Collection
     */
    protected function getConditions(Collection $attributes, Collection $keys)
    {
        return $attributes->map(function ($attribute) use ($keys) {
            foreach (array_keys($attribute) as $field) {
                if (!$keys->contains($field)) {
                    unset($attribute[$field]);
                }
            }

            return $attribute;
        });
    }

    /**
     * @param Collection $attributes
     * @param Collection $keys
     * @return Collection
     */
    protected function getExists(Collection $attributes, Collection $keys)
    {
        $conditions = $this->getConditions($attributes, $keys);

        $query = DB::table(static::getTable());

        $query->where(function (Builder $query) use ($conditions) {
            $conditions->each(function ($item) use ($query) {
                $query->orWhere(function (Builder $query) use ($item) {
                    $query->where($item);
                });
            });
        });

        return $query->get();
    }

    /**
     * @param Collection $attributes
     * @param Collection $exists
     * @param Collection $keys
     * @return Collection
     */
    protected function partitionUpdateInsert(Collection $attributes, Collection $exists, Collection $keys)
    {
        return $attributes->partition(function ($attribute) use ($exists, $keys) {
            foreach ($keys as $key) {
                $exists = $exists->where($key, $attribute[$key]);
            }

            return $exists->isNotEmpty();
        });
    }

    /**
     * @param Collection $attributes
     * @return Collection
     */
    protected function insertAttributes(Collection $attributes)
    {
        if ($attributes->isEmpty()) {
            return $attributes;
        }

        $ids = $this->generateIds($attributes->count());

        $insert = $attributes->values()->map(function ($item, $index) use ($ids) {
            $item['created_at'] = $item['updated_at'] = Carbon::now();

            if (!$this->incrementing && empty($item['id'])) {
                $item['id'] = $ids[$index];
            }

            return $item;
        });
        $count = $insert->max(function ($item) {
            return count($item);
        });
        $fullItem = collect(
            $insert->first(
                function ($item) use ($count) {
                    return count($item) == $count;
                }
            )
        );
        $insert->transform(function ($item) use ($fullItem) {
            $missing = $fullItem->diffKeys($item);
            if ($missing->isNotEmpty()) {
                foreach ($missing->keys() as $key) {
                    $item[$key] = DB::raw("DEFAULT($key)");
                }
            }

            return $item;
        });
        $this->insert($insert->toArray());

        return $insert;
    }

    /**
     * @return array
     */
    protected function resultForNotImport()
    {
        return [
            'update' => 0,
            'insert' => 0,
        ];
    }
}
