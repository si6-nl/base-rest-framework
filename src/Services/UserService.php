<?php

namespace Si6\Base\Services;

class UserService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.user');
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function detail($id)
    {
        $response = $this->get('users/' . $id);

        return $response->data ?? null;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function info($id)
    {
        $response = $this->get('users/' . $id . '/info');

        return $response->data ?? null;
    }

    public function updateBalance($id, array $param)
    {
        $this->internal()->put("users/$id/balance", $param);
    }

    public function getProfiles(array $param)
    {
        $response = $this->get('profiles', $param);

        return $response->data ?? null;
    }
}
