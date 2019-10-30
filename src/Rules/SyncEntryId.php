<?php

namespace Si6\Base\Rules;

use Illuminate\Contracts\Validation\Rule;
use Si6\Base\Services\RaceService;

class SyncEntryId implements Rule
{
    public function passes($attribute, $value)
    {
        /** @var RaceService $raceService */
        $raceService = app(RaceService::class)->getInstance();

        $raceService->validateSyncEntryId($value);

        return true;
    }

    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
