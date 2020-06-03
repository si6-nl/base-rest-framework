<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Si6\Base\Exceptions\PlatformException;

class PlatformService extends ExternalService
{
    use SingletonInstance;

    public function __construct()
    {
        parent::__construct();
    }

    protected function baseUri()
    {
        $uri = config('external.platform.base_uri');

        return Str::finish($uri, '/');
    }

    protected function setDefaultHeaders()
    {
        $this->options['headers'] = [
            'X-Api-Id'      => config('external.platform.api_id'),
            'X-Api-Key'     => config('external.platform.api_key'),
            'Authorization' => config('external.platform.authorization'),
        ];
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return mixed
     * @throws GuzzleException
     * @throws PlatformException
     */
    protected function handleRequest($method, $url, $options)
    {
        $url     = trim($url, '/');
        $logOptions = $options = array_merge($this->options, $options);
        unset($logOptions['headers']);

        Log::info('SEND_REQUEST_TO_PLATFORM', [
            'method'    => $method,
            'url'       => $url,
            'options'   => $logOptions
        ]);

        $response = $this->client->request($method, $url, $options);

        $data = json_decode($response->getBody()->getContents(), true);
        if (!isset($data['result_code'])) {
            throw new PlatformException($options, null, $data['message'] ?? '', 900);
        }

        if ($data['result_code'] != 100) {
            Log::info('SEND_REQUEST_TO_PLATFORM_FAILED', $data);
            $this->syncException($data);
        }

        Log::info('SEND_REQUEST_TO_PLATFORM_SUCCEED', $data);

        return $data;
    }

    /**
     * @param  RequestException|mixed  $exception
     * @throws PlatformException
     */
    protected function handleException($exception)
    {
        $this->syncException((array)$exception);
    }

    /**
     * @param  RequestException|mixed  $exception
     * @throws PlatformException
     */
    protected function syncException($exception)
    {
        if (!$this->syncException) {
            return;
        }

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message    = $exception instanceof RequestException ? $exception->getMessage() : '';
        $data       = null;

        // TODO: handle error with result_code

        Log::error("Response platform exception ", $exception);
        throw new PlatformException($data, $statusCode, $message, $exception['result_code'] ?? null);
    }
}
