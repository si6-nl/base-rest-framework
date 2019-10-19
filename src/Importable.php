<?php

namespace Si6\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

trait Importable
{
    protected function import($attributes, $keys = [])
    {
        /**
         * @var Collection $attributes
         * @var Collection $keys
         */
        list($attributes, $keys) = $this->prepareParamImporting($attributes, $keys);

        /** @var Collection $exists */
        $exists = $this->getExists($attributes, $keys);

        /**
         * @var Collection $updateAttributes
         * @var Collection $insertAttributes
         */
        list($updateAttributes, $insertAttributes) = $this->partitionUpdateInsert($attributes, $exists, $keys);

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
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getExists(Collection $attributes, Collection $keys)
    {
        $conditions = $this->getConditions($attributes, $keys);
        /** @var Builder $query */
        $query = static::where(function (Builder $query) use ($conditions) {
            $conditions->each(function ($item) use ($query) {
                $query->orWhere($item);
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
     */
    protected function insertAttributes(Collection $attributes)
    {
        $insert = $attributes->map(function ($item) {
            $item['id']         = $this->generateId();
            $item['created_at'] = $item['updated_at'] = Carbon::now();

            return $item;
        });

        $this->insert($insert->toArray());
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
