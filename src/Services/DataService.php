<?php

namespace Si6\Base\Services;

class DataService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.data');
    }
}
