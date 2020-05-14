<?php

namespace Si6\Base;

use DateTimeInterface;
use Exception;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                /** @var Model $model */
                if (!$model->getIncrementing() && $model->getKeyName()) {
                    $model->{$model->getKeyName()} = $model->generateId();
                }
                self::handleActionByColumn($model);
            }
        );
    }

    /**
     * @return string
     */
    protected function getCreatedByColumn()
    {
        return self::CREATED_BY;
    }

    /**
     * @return string
     */
    protected function getUpdatedByColumn()
    {
        return self::UPDATED_BY;
    }

    /**
     * @param Model $model
     */
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
        $next = $this->getNextSequence();

        return UniqueIdentity::id($next);
    }

    /**
     * @param $count
     * @return array
     */
    protected function generateIds($count)
    {
        $next = $this->getNextSequence($count);

        return collect(range($next - $count + 1, $next))->map(function ($id) {
            return UniqueIdentity::id($id);
        })
            ->toArray();
    }

    /**
     * @param int $count
     * @return mixed
     */
    private function getNextSequence($count = 1)
    {
        return DB::transaction(
            function () use ($count) {
                $sequent = DB::table('entity_sequences')
                    ->select('next_value')
                    ->where('entity', $this->getTable())
                    ->lockForUpdate()
                    ->first();

                if (!$sequent) {
                    DB::table('entity_sequences')
                        ->insert(
                            [
                                'entity'     => $this->getTable(),
                                'next_value' => $count + 1,
                            ]
                        );

                    return $count;
                }

                $nextValue = $sequent->next_value + $count;

                DB::table('entity_sequences')
                    ->where('entity', $this->getTable())
                    ->update(['next_value' => $nextValue]);

                return $nextValue - 1;
            }
        );
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format(DATE_ISO8601);
    }
}
