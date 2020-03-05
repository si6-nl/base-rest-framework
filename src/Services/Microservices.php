<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Si6\Base\Exceptions\MicroservicesException;

abstract class Microservices
{
    use HttpClient;

    protected $isInternal = false;

    public function __construct()
    {
        $this->setupClient();
    }

    protected function setDefaultHeaders()
    {
        $this->options['headers'] = [
            'Content-type' => 'application/json',
            'Accept'       => 'application/json',
        ];
    }

    protected function baseUri()
    {
        $host = $this->getHost();

        return Str::finish($host, '/');
    }

    abstract protected function getHost();

    public function syncAuthorization()
    {
        $this->options['headers']['Authorization'] = request()->header('Authorization');

        return $this;
    }

    /**
     * @return $this
     */
    public function internal()
    {
        $this->isInternal = true;

        return $this;
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return mixed
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function handleRequest($method, $url, $options)
    {
        $url = $this->prepareUrl($url);
        $this->syncAuthorization();
        $options = array_merge($this->options, $options);

        $response = $this->client->request($method, $url, $options);
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $data = json_decode($response->getBody()->getContents());
            throw new MicroservicesException($data, $response->getStatusCode());
        }

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param  RequestException  $exception
     * @throws MicroservicesException
     */
    protected function handleException(RequestException $exception)
    {
        $this->syncException($exception);
    }

    /**
     * @param  RequestException  $exception
     * @throws MicroservicesException
     */
    protected function syncException(RequestException $exception)
    {
        if (!$this->syncException) {
            return;
        }

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message    = $exception->getMessage();
        $data       = null;

        if ($exception->hasResponse()) {
            $response   = $exception->getResponse();
            $statusCode = $response->getStatusCode();
            $data       = json_decode($response->getBody()->getContents());
        }

        throw new MicroservicesException($data, $statusCode, $message);
    }

    protected function prepareUrl($url)
    {
        if (Str::startsWith($url, 'http')) {
            return $url;
        }

        $url = trim($url, '/');

        // append default version v1
        if (!preg_match('/^v[2-9]/', Str::substr($url, 0, 2))) {
            if ($this->isInternal) {
                $url = 'internal/' . $url;
            }

            $url = 'v1/' . $url;
        }

        return $url;
    }
}
