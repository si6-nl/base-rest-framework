<?php

namespace Si6\Base;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Si6\Base\Utils\UniqueIdentity;
use Illuminate\Support\Facades\DB;

abstract class Model extends EloquentModel
{
    public $incrementing = false;

    public $createdBy = false;

    public $updatedBy = false;

    const CREATED_BY = 'created_by';

    const UPDATED_BY = 'updated_by';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /** @var Model $model */
            if (!$model->getIncrementing() && $model->getKeyName()) {
                $model->{$model->getKeyName()} = self::generateId($model->getTable());
            }
            if ($model->createdBy) {
                $model->{$model->getCreatedByColumn()} = Auth::id();
            }
            if ($model->updatedBy) {
                $model->{$model->getUpdatedByColumn()} = Auth::id();
            }
        });
    }

    protected function getCreatedByColumn()
    {
        return self::CREATED_BY;
    }

    protected function getUpdatedByColumn()
    {
        return self::UPDATED_BY;
    }

    public static function generateId($entity)
    {
        return DB::transaction(function () use ($entity) {
            $nextValue = Model::getNextSequence($entity);
            $id        = UniqueIdentity::id($nextValue);
            Model::updateSequence($entity);

            return $id;
        });
    }

    private static function getNextSequence($entity)
    {
        $sequent = DB::table('entity_sequences')
            ->select('next_value')
            ->where('entity', $entity)
            ->lockForUpdate()
            ->first();

        if (isset($sequent->next_value)) {
            return $sequent->next_value;
        }

        DB::table('entity_sequences')
            ->insert([
                'entity'     => $entity,
                'next_value' => 1,
            ]);

        return 1;
    }

    private static function updateSequence($entity)
    {
        DB::table('entity_sequences')
            ->where('entity', $entity)
            ->increment('next_value');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(DATE_ISO8601);
    }

    public static function multiUpdate($attributes, string $index = null)
    {
        if (!$attributes instanceof Collection) {
            $attributes = collect($attributes);
        }

        if ($attributes->isEmpty()) {
            return;
        }

        $model = new static();
        $table = DB::getTablePrefix() . $model->getTable();
        $index = $index ?: $model->getKeyName();

        $sets     = [];
        $cases    = [];
        $param    = [];
        $whereIn  = [];
        $bindings = [];

        foreach ($attributes as $values) {
            if (empty($values[$index])) {
                continue;
            }
            foreach ($values as $field => $value) {
                if ($field !== $index) {
                    $cases[$field][] = "WHEN ? THEN ?";
                    $param[$field][] = [$values[$index], $value];
                }
            }

            $whereIn[] = "$values[$index]";
        }

        foreach ($cases as $field => $case) {
            $case   = implode(' ', $case);
            $sets[] = "`{$field}` = CASE `{$index}` {$case} END";
            foreach ($param[$field] as $value) {
                $bindings[] = $value[0];
                $bindings[] = $value[1];
            }
        }

        $sets[]     = "`updated_at` = ?";
        $sets       = implode(',', $sets);
        $bindings[] = Carbon::now();
        $whereIn    = implode(',', $whereIn);

        $query = /** @lang text */
            "UPDATE `{$table}` SET {$sets} WHERE `{$index}` IN ({$whereIn})";

        DB::update($query, $bindings);
    }
}
