<?php

namespace Si6\Base\Services;

use Exception;

class DataService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.data');
    }

    public function validatePrefectureId($prefectureId)
    {
        $this->get('addresses/prefectures/validation', ['prefecture_id' => $prefectureId]);
    }

    public function validateCountryId($countryId)
    {
        $this->get('data/countries/validation', ['country_id' => $countryId]);
    }

    /**
     * @param array $param
     * @return array
     */
    public function mastersWithoutPagination(array $param)
    {
        $response = $this->get('masters/all', $param);

        return $response->data ?? [];
    }

    /**
     * @param $id
     * @param $exception
     */
    public function platformNotificationFailByInternalError($id, $exception)
    {
        $this->post("internal/platforms/notifications/$id/error", ['exception' => $exception]);
    }

    /**
     * @param $id
     * @param $exception
     */
    public function platformNotificationFailByEmptyData($id, $exception)
    {
        $this->post("internal/platforms/notifications/$id/empty", ['exception' => $exception]);
    }

    /**
     * @param $id
     */
    public function platformNotificationSuccess($id)
    {
        $this->post("internal/platforms/notifications/$id/success");
    }

    /**
     * @param $param
     * @param $response
     */
    public function handlePlatformNotificationAfterQueueDone($param, $response)
    {
        if (!empty($param['platform_notification_id'])) {
            if (!empty($response->data) && $response->data === true) {
                $this->platformNotificationSuccess($param['platform_notification_id']);
            } elseif (isset($response->data->exception)) {
                $this->platformNotificationFailByEmptyData(
                    $param['platform_notification_id'],
                    $response->data->exception
                );
            }
        }
    }

    /**
     * @param $param
     * @param Exception $exception
     */
    public function handlePlatformNotificationAfterQueueFail($param, Exception $exception)
    {
        if (!empty($param['platform_notification_id'])) {
            $this->platformNotificationFailByInternalError(
                $param['platform_notification_id'],
                $exception->getMessage()
            );
        }
    }
}
