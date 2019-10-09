<?php

namespace Si6\Base\Rules;

use Illuminate\Contracts\Validation\Rule;
use Si6\Base\Services\DataService;

class CountryId implements Rule
{
    public function passes($attribute, $value)
    {
        /** @var DataService $dataService */
        $dataService = app(DataService::class)->getInstance();

        $dataService->validateCountryId($value);

        return true;
    }

    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
