<?php

namespace Si6\Base\DataTransferObjects;

class TagDTO
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $status;
    /**
     * @var TagTranslationDTO[]
     */
    private $translations;

    /**
     * TagDTO constructor.
     *
     * @param int $id
     * @param int $status
     * @param array $translations
     */
    public function __construct(int $id, int $status, array $translations)
    {
        $this->id     = $id;
        $this->status = $status;

        $this->setTranslations($translations);
    }

    /**
     * @param array $translations
     */
    public function setTranslations(array $translations): void
    {
        foreach ($translations as $translation) {
            $this->translations[] = new TagTranslationDTO(
                $this->id,
                $translation['language_code'],
                $translation['name']
            );
        }
    }

    /**
     * @return array
     */
    public function getImportableArray()
    {
        return [
            'id'     => $this->id,
            'status' => $this->status,
        ];
    }

    /**
     * @return array
     */
    public function getTranslationsImportableArray()
    {
        return collect($this->translations)
            ->map(
                function (TagTranslationDTO $dto) {
                    return $dto->getImportableArray();
                }
            )
            ->toArray();
    }
}
