<?php

namespace Si6\Base\DataTransferObjects;

class CategoryDTO
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var int
     */
    private $type;
    /**
     * @var int
     */
    private $status;
    /**
     * @var CategoryTranslationDTO[]
     */
    private $translations;

    /**
     * CategoryDTO constructor.
     *
     * @param int $id
     * @param int $type
     * @param int $status
     * @param array $translations
     */
    public function __construct(int $id, int $type, int $status, array $translations)
    {
        $this->id     = $id;
        $this->type   = $type;
        $this->status = $status;

        $this->setTranslations($translations);
    }

    /**
     * @param array $translations
     */
    public function setTranslations(array $translations): void
    {
        foreach ($translations as $translation) {
            $this->translations[] = new CategoryTranslationDTO(
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
            'type'   => $this->type,
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
                function (CategoryTranslationDTO $dto) {
                    return $dto->getImportableArray();
                }
            )
            ->toArray();
    }
}
