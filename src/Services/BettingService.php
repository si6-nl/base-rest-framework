<?php

namespace Si6\Base\Services;

class BettingService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.betting');
    }
}
