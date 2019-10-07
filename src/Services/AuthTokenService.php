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
        $response = $this->post('authentication');

        $user = new User();
        $user->fill((array)($response->data ?? []));

        return $user;
    }
}
