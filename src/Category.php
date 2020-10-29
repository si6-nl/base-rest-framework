<?php

namespace Si6\Base;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Si6\Base\Enums\Language;

/**
 * \Si6\Base\Category
 *
 * @property int $id
 * @property string $type
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CategoryTranslation|null $attributes
 * @property-read Collection|CategoryTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereStatus($value)
 * @method static Builder|Category whereType($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'type',
        'status',
    ];

    public function attributes()
    {
        return $this->hasOne(CategoryTranslation::class)
            ->orderByRaw("FIELD(language_code, " . Language::getOrderByRaw(app()->getLocale()) . ")");
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }
}
