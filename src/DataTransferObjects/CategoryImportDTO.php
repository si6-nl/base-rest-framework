<?php

namespace Si6\Base\DataTransferObjects;

class CategoryImportDTO
{
    /**
     * @var CategoryDTO[]
     */
    private $categories;

    /**
     * CategoryImportDTO constructor.
     *
     * @param array $categories
     */
    public function __construct(array $categories)
    {
        foreach ($categories as $category) {
            $this->categories[] = new CategoryDTO(
                $category['id'],
                $category['type'],
                $category['status'],
                $category['translations']
            );
        }
    }

    /**
     * @return array
     */
    public function getImportableArray()
    {
        return collect($this->categories)
            ->map(
                function (CategoryDTO $dto) {
                    return $dto->getImportableArray();
                }
            )
            ->toArray();
    }

    /**
     * @return array
     */
    public function getTranslationsImportableArray()
    {
        return collect($this->categories)->map(
            function (CategoryDTO $dto) {
                return $dto->getTranslationsImportableArray();
            }
        )
            ->flatten(1)
            ->toArray();
    }
}
