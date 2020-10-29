<?php

namespace Si6\Base;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * \Si6\Base\CategoryTranslation
 *
 * @property int $id
 * @property int $category_id
 * @property string $language_code
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|CategoryTranslation newModelQuery()
 * @method static Builder|CategoryTranslation newQuery()
 * @method static Builder|CategoryTranslation query()
 * @method static Builder|CategoryTranslation whereCategoryId($value)
 * @method static Builder|CategoryTranslation whereCreatedAt($value)
 * @method static Builder|CategoryTranslation whereId($value)
 * @method static Builder|CategoryTranslation whereLanguageCode($value)
 * @method static Builder|CategoryTranslation whereName($value)
 * @method static Builder|CategoryTranslation whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CategoryTranslation extends Model
{
    protected $table = 'category_translations';
}
