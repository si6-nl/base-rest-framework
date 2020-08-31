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
        ];

        if (!in_array(app()->environment(), ['staging', 'production'])) {
            $this->options['headers']['Authorization'] = config('external.platform.authorization');
        }
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
        $url = trim($url, '/');

        $response = $this->client->request($method, $url, $options);

        $data = json_decode($response->getBody()->getContents(), true);
        if (!isset($data['result_code'])) {
            throw new PlatformException($options, null, $data['message'] ?? '', 900);
        }

        if ($data['result_code'] == 900) {
            Log::info('SEND_REQUEST_TO_PLATFORM_FAILED', $data);
            $this->syncException($data);
        }

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

        Log::error("Response platform exception ", $exception);
        throw new PlatformException($data, $statusCode, $message, $exception['result_code'] ?? null);
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function getEvents(array $param)
    {
        $query = [];

        if (!empty($param['sync_event_id'])) {
            $query = ['hold_id' => $param['sync_event_id']];
        } elseif (!empty($param['year']) && !empty($param['month'])) {
            $query = [
                'year' => $param['year'],
                'month' => $param['month'],
            ];
        }

        if (empty($query)) {
            return null;
        }

        return $this->get('portal/calendar/seller', $query);
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function getEventPlayers(array $param)
    {
        return $this->get('portal/mediator_player', ['hold_id' => $param['sync_event_id']]);
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function getRaceEntryTable(array $param)
    {
        return $this->get(
            'portal/race_table/seller',
            [
                'hold_id'       => $param['sync_event_id'],
                'hold_id_daily' => $param['sync_event_day_id'],
            ]
        );
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function getRaceDetail(array $param)
    {
        return $this->get('portal/race_detail', ['entries_id' => $param['sync_entry_id']]);
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function getRaceResult(array $param)
    {
        return $this->get('portal/race_result', ['entries_id' => $param['sync_entry_id']]);
    }

    /**
     * @param array $param
     * @return mixed
     */
    public function getEventTrial(array $param)
    {
        return $this->get('portal/time_trial_result', ['hold_id' => $param['sync_event_id']]);
    }
}
