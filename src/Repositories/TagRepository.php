<?php

namespace Si6\Base\Repositories;

use Si6\Base\BaseRepository;
use Si6\Base\DataTransferObjects\TagImportDTO;
use Si6\Base\Tag;
use Si6\Base\TagTranslation;

class TagRepository extends BaseRepository
{
    /**
     * @param TagImportDTO $dto
     */
    public function import(TagImportDTO $dto)
    {
        Tag::import($dto->getImportableArray(), ['id']);
        TagTranslation::import($dto->getTranslationsImportableArray(), ['tag_id', 'language_code']);
    }
}
