<?php

namespace Si6\Base\Services;

use Si6\Base\User;

class AuthTokenService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.auth');
    }

    public function authenticate()
    {
        $response = $this->internal()->post('users/auth');

        $user = null;

        if (!empty($response->data)) {
            $user = new User();
            $user->fill((array)($response->data ?? []));
        }

        return $user;
    }
}
