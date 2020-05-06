<?php

namespace Si6\Base\Services;

class BlogService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.blog');
    }
}
