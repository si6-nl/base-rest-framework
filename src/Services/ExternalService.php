<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\RequestException;

abstract class ExternalService
{
    use HttpClient;

    public function __construct()
    {
        $this->setupClient();
    }

    abstract protected function baseUri();

    abstract protected function setDefaultHeaders();

    abstract protected function handleRequest($method, $url, $options);

    abstract protected function handleException(RequestException $exception);
}
