<?php

namespace Si6\Base\Services;

class AuthService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.auth');
    }

    public function getUsers(array $param)
    {
        $response = $this->get('users', $param);

        return $response->data ?? [];
    }

    public function validateUserId($userId)
    {
        $this->get('users/validation', ['user_id' => $userId]);
    }
}
