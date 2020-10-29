<?php

namespace Si6\Base\DataTransferObjects;

class TagTranslationDTO
{
    /**
     * @var int
     */
    private $tagId;
    /**
     * @var string
     */
    private $languageCode;
    /**
     * @var string
     */
    private $name;

    /**
     * TagTranslationDTO constructor.
     *
     * @param int $tagId
     * @param string $languageCode
     * @param string $name
     */
    public function __construct(int $tagId, string $languageCode, string $name)
    {
        $this->tagId        = $tagId;
        $this->languageCode = $languageCode;
        $this->name         = $name;
    }

    /**
     * @return array
     */
    public function getImportableArray()
    {
        return [
            'tag_id'        => $this->tagId,
            'language_code' => $this->languageCode,
            'name'          => $this->name,
        ];
    }
}
