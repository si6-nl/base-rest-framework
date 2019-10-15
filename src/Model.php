<?php

namespace Si6\Base;

use DateTimeInterface;
use Exception;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Schema;
use Si6\Base\Utils\UniqueIdentity;
use Throwable;

abstract class Model extends EloquentModel
{
    use MultipleUpdatable;
    use Importable;

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
                $model->{$model->getKeyName()} = $model->generateId();
            }
            self::handleActionByColumn($model);
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

    protected static function handleActionByColumn(Model $model)
    {
        $id = null;

        try {
            $id = Auth::id();
        } catch (Exception $exception) {
            if (Schema::hasColumn($model->getTable(), 'user_id')) {
                $id = $model->getAttribute('user_id');
            }
        }

        if ($model->createdBy) {
            $model->{$model->getCreatedByColumn()} = $id;
        }
        if ($model->updatedBy) {
            $model->{$model->getUpdatedByColumn()} = $id;
        }
    }

    /**
     * @return mixed
     * @throws Throwable
     */
    protected function generateId()
    {
        return DB::transaction(function () {
            $next = $this->getNextSequence();
            $id   = UniqueIdentity::id($next);
            $this->updateSequence();

            return $id;
        });
    }

    private function getNextSequence()
    {
        $sequent = DB::table('entity_sequences')
            ->select('next_value')
            ->where('entity', $this->getTable())
            ->lockForUpdate()
            ->first();

        if (isset($sequent->next_value)) {
            return $sequent->next_value;
        }

        DB::table('entity_sequences')
            ->insert([
                'entity'     => $this->getTable(),
                'next_value' => 1,
            ]);

        return 1;
    }

    private function updateSequence()
    {
        DB::table('entity_sequences')
            ->where('entity', $this->getTable())
            ->increment('next_value');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(DATE_ISO8601);
    }
}
