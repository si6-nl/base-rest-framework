<?php

namespace Si6\Base;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * \Si6\Base\TagTranslation
 *
 * @property int $id
 * @property int $tag_id
 * @property string $language_code
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|TagTranslation newModelQuery()
 * @method static Builder|TagTranslation newQuery()
 * @method static Builder|TagTranslation query()
 * @method static Builder|TagTranslation whereTagId($value)
 * @method static Builder|TagTranslation whereCreatedAt($value)
 * @method static Builder|TagTranslation whereId($value)
 * @method static Builder|TagTranslation whereLanguageCode($value)
 * @method static Builder|TagTranslation whereName($value)
 * @method static Builder|TagTranslation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class TagTranslation extends Model
{
    protected $table = 'tag_translations';
}
