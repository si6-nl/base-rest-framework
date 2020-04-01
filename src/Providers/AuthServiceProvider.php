<?php

namespace Si6\Base\Providers;

use Exception;
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

            try {
                $user = $authService->authenticate();
            } catch (Exception $e) {
                $user = null;
            }

            return new AccessTokenGuard($user);
        });
    }
}
