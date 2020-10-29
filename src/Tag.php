<?php

namespace Si6\Base;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Si6\Base\Enums\Language;

/**
 * \Si6\Base\Tag
 *
 * @property int $id
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TagTranslation|null $attributes
 * @property-read Collection|TagTranslation[] $translations
 * @property-read int|null $translations_count
 * @method static Builder|Tag newModelQuery()
 * @method static Builder|Tag newQuery()
 * @method static Builder|Tag query()
 * @method static Builder|Tag whereCreatedAt($value)
 * @method static Builder|Tag whereId($value)
 * @method static Builder|Tag whereStatus($value)
 * @method static Builder|Tag whereType($value)
 * @method static Builder|Tag whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'status',
    ];

    public function attributes()
    {
        return $this->hasOne(TagTranslation::class)
            ->orderByRaw("FIELD(language_code, " . Language::getOrderByRaw(app()->getLocale()) . ")");
    }

    public function translations()
    {
        return $this->hasMany(TagTranslation::class);
    }
}
