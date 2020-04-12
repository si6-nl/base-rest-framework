<?php

namespace Si6\Base\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Si6\Base\AccessTokenGuard;
use Si6\Base\Services\AuthTokenService;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Auth::extend('access_token', function () {
            /** @var AuthTokenService $authService */
            $authService = app(AuthTokenService::class)->getInstance();

            $user = $authService->authenticate();

            return new AccessTokenGuard($user);
        });
    }
}
