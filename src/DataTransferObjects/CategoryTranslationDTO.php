<?php

namespace Si6\Base\DataTransferObjects;

class CategoryTranslationDTO
{
    /**
     * @var int
     */
    private $categoryId;
    /**
     * @var string
     */
    private $languageCode;
    /**
     * @var string
     */
    private $name;

    /**
     * CategoryTranslationDTO constructor.
     *
     * @param int $categoryId
     * @param string $languageCode
     * @param string $name
     */
    public function __construct(int $categoryId, string $languageCode, string $name)
    {
        $this->categoryId   = $categoryId;
        $this->languageCode = $languageCode;
        $this->name         = $name;
    }

    /**
     * @return array
     */
    public function getImportableArray()
    {
        return [
            'category_id'   => $this->categoryId,
            'language_code' => $this->languageCode,
            'name'          => $this->name,
        ];
    }
}
