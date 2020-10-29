<?php

namespace Si6\Base\Http\Requests;

use BenSampo\Enum\Rules\EnumValue;
use Si6\Base\DataTransferObjects\CategoryImportDTO;
use Si6\Base\Enums\CategoryStatus;
use Si6\Base\Enums\CategoryType;

class CategoryImportRequest extends FormRequest
{
    public function rules()
    {
        return [
            'categories'                                => ['required', 'array'],
            'categories.*.id'                           => array_merge(['required'], $this->unsignedBigInteger()),
            'categories.*.type'                         => ['required', new EnumValue(CategoryType::class), false],
            'categories.*.status'                       => ['required', new EnumValue(CategoryStatus::class, false)],
            'categories.*.translations'                 => ['required', 'array'],
            'categories.*.translations.*.name'          => ['required', 'string'],
            'categories.*.translations.*.language_code' => ['required', 'string'],
        ];
    }

    public function dto()
    {
        return new CategoryImportDTO((array)$this->input('categories'));
    }
}
