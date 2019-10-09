<?php

namespace Si6\Base\Rules;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Validation\Rule;
use Si6\Base\Exceptions\MicroservicesException;
use Si6\Base\Services\AuthService;

class UserId implements Rule
{
    public function passes($attribute, $value)
    {
        /** @var AuthService $authService */
        $authService = app(AuthService::class)->getInstance();

        $authService->validateUserId($value);

        return true;
    }

    public function message()
    {
        return 'The selected :attribute is invalid.';
    }
}
