<?php

namespace Si6\Base\Services;

class RaceService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.race');
    }
}
