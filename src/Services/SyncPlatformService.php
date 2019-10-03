<?php

namespace Si6\Base\Services;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Si6\Base\Exceptions\SyncPlatformException;

class SyncPlatformService extends ExternalService
{
    use SingletonInstance;

    public function __construct()
    {
        parent::__construct();
    }

    protected function baseUri()
    {
        $uri = config('external.sync_platform.base_uri');

        return Str::finish($uri, '/');
    }

    protected function setDefaultHeaders()
    {
        $this->options['headers'] = [
            'X-Api-Id'      => config('external.sync_platform.api_id'),
            'X-Api-Key'     => config('external.sync_platform.api_key'),
            'Authorization' => config('external.sync_platform.authorization'),
        ];
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return mixed
     * @throws GuzzleException
     * @throws SyncPlatformException
     */
    protected function handleRequest($method, $url, $options)
    {
        $url     = trim($url, '/');
        $options = array_merge($this->options, $options);

        $response = $this->client->request($method, $url, $options);

        $data = json_decode($response->getBody()->getContents());
        if (!isset($data->result_code)) {
            throw new SyncPlatformException(null, null, $data->message ?? '');
        }

        if ($data->result_code != 100) {
            $this->syncException($data);
        }

        return $data;
    }

    /**
     * @param  RequestException|mixed  $exception
     * @throws SyncPlatformException
     */
    protected function handleException($exception)
    {
        $this->syncException($exception);
    }

    /**
     * @param  RequestException|mixed  $exception
     * @throws SyncPlatformException
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

        throw new SyncPlatformException($data, $statusCode, $message);
    }
}
