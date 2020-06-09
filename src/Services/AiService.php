<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Si6\Base\Exceptions\AiServiceException;

class AiService extends ExternalService
{
    use SingletonInstance;

    public function __construct()
    {
        parent::__construct();
        // TODO: using token from auth()->user()->admin
        $this->setToken('Wgff3bcc3d3a03f44e3d8377f9247b0ad155');
    }

    protected function baseUri()
    {
        $uri = config('external.ai.base_uri');

        return Str::finish($uri, '/');
    }

    protected function setDefaultHeaders()
    {
        //
    }

    public function setToken($token)
    {
        $this->options['headers']['keirin_api_auth_token'] = $token;
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return mixed
     * @throws GuzzleException
     */
    protected function handleRequest($method, $url, $options)
    {
        $url        = trim($url, '/');
        $logOptions = $options = array_merge($this->options, $options);
        unset($logOptions['headers']);

        Log::info(
            'SEND_REQUEST_TO_AI_SERVICE',
            [
                'method'  => $method,
                'url'     => $url,
                'options' => $logOptions,
            ]
        );

        $response = $this->client->request($method, $url, $options);

        $data = json_decode($response->getBody()->getContents(), true);

        Log::info('SEND_REQUEST_TO_AI_SERVICE_SUCCEED', $data);

        return $data;
    }

    /**
     * @param RequestException $exception
     * @throws AiServiceException
     */
    protected function handleException(RequestException $exception)
    {
        if (!$this->syncException) {
            return;
        }

        $data = json_decode($exception->getResponse()->getBody()->getContents(), true);

        throw new AiServiceException(
            $data,
            $exception->getResponse()->getStatusCode(),
            $content['message'] ?? '',
            $exception->getCode()
        );
    }
}
