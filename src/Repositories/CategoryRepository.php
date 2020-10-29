<?php

namespace Si6\Base\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Si6\Base\BaseRepository;
use Si6\Base\Category;
use Si6\Base\CategoryTranslation;
use Si6\Base\Criteria\CategoryCriteria;
use Si6\Base\Criteria\CategorySortOptions;
use Si6\Base\DataTransferObjects\CategoryImportDTO;
use Si6\Base\Exceptions\CriteriaNotHasModel;

class CategoryRepository extends BaseRepository
{
    /**
     * @param array $param
     * @return LengthAwarePaginator
     * @throws CriteriaNotHasModel
     */
    public function get(array $param)
    {
        $param['sort'] = '-updated_at,-id';

        $query = $this->queryCategory($param);

        $query->with(
            [
                'translations' => function ($query) {
                    /** @var Builder $query */
                    $query->select(['category_id', 'language_code', 'name']);
                }
            ]
        );

        return $this->pagination($query);
    }

    /**
     * @param array $param
     * @return Category|Builder
     * @throws CriteriaNotHasModel
     */
    protected function queryCategory(array $param)
    {
        $query = Category::query();

        (new CategoryCriteria($param))->applyQuery($query);
        (new CategorySortOptions($param))->applyQuery($query);

        return $query;
    }

    /**
     * @param array $param
     * @return Category[]|Builder[]|Collection
     * @throws CriteriaNotHasModel
     */
    public function all(array $param)
    {
        $query = $this->queryCategory($param);

        $query->with(
            [
                'translations' => function ($query) {
                    /** @var Builder $query */
                    $query->select(['category_id', 'language_code', 'name']);
                }
            ]
        );

        return $query->get();
    }

    /**
     * @param array $param
     * @return Builder[]|Collection
     * @throws CriteriaNotHasModel
     */
    public function getWithOutPagination(array $param)
    {
        $query = $this->queryCategory($param);

        $query->select(
            [
                'id',
                'type',
                'status',
            ]
        );
        $query->with(
            [
                'attributes' => function ($query) {
                    /** @var Builder $query */
                    $query->select(['category_id', 'name']);
                },
            ]
        );

        return $query->get();
    }

    /**
     * @param CategoryImportDTO $dto
     */
    public function import(CategoryImportDTO $dto)
    {
        Category::import($dto->getImportableArray(), ['id']);
        CategoryTranslation::import($dto->getTranslationsImportableArray(), ['category_id', 'language_code']);
    }
}
