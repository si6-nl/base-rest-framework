<?php

namespace Si6\Base\DataTransferObjects;

class TagImportDTO
{
    /**
     * @var TagDTO[]
     */
    private $categories;

    /**
     * TagImportDTO constructor.
     *
     * @param array $categories
     */
    public function __construct(array $categories)
    {
        foreach ($categories as $Tag) {
            $this->categories[] = new TagDTO(
                $Tag['id'],
                $Tag['status'],
                $Tag['translations']
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
                function (TagDTO $dto) {
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
            function (TagDTO $dto) {
                return $dto->getTranslationsImportableArray();
            }
        )
            ->flatten(1)
            ->toArray();
    }
}
