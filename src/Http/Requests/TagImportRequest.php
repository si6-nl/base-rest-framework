<?php

namespace Si6\Base\Http\Requests;

use BenSampo\Enum\Rules\EnumValue;
use Si6\Base\DataTransferObjects\TagImportDTO;
use Si6\Base\Enums\TagStatus;

class TagImportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tags'                                => ['required', 'array'],
            'tags.*.id'                           => array_merge(['required'], $this->unsignedBigInteger()),
            'tags.*.status'                       => ['required', new EnumValue(TagStatus::class, false)],
            'tags.*.translations'                 => ['required', 'array'],
            'tags.*.translations.*.name'          => ['required', 'string'],
            'tags.*.translations.*.language_code' => ['required', 'string'],
        ];
    }

    public function dto()
    {
        return new TagImportDTO((array)$this->input('tags'));
    }
}
