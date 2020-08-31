<?php

namespace Si6\Base\Services;

class RaceService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.race');
    }

    /**
     * @param $syncEntryId
     */
    public function validateSyncEntryId($syncEntryId)
    {
        $this->get('races/entries/validation', ['entries_id' => $syncEntryId]);
    }

    /**
     * @param $raceId
     */
    public function validateRaceId($raceId)
    {
        $this->get('races/validation', ['race_id' => $raceId]);
    }

    /**
     * @param array $param
     * @return mixed|null
     */
    public function detail(array $param = [])
    {
        $response = $this->get("internal/races/detail", $param);

        return $response->data ?? null;
    }

    /**
     * @param $syncEntryId
     * @return mixed|null
     */
    public function detailBySyncEntryId($syncEntryId)
    {
        $response = $this->get("internal/races/entries/$syncEntryId");

        return $response->data ?? null;
    }

    /**
     * @param $syncEventDayId
     * @return mixed|null
     */
    public function detailDayBySyncEventDayId($syncEventDayId)
    {
        $response = $this->get("internal/days/sync/$syncEventDayId");

        return $response->data ?? null;
    }

    /**
     * @param array $param
     * @return array
     */
    public function getSyncEventIds(array $param)
    {
        $response = $this->get('internal/events/sync/ids', $param);

        return $response->data ?? [];
    }

    /**
     * @param array $param
     * @return array
     */
    public function getSyncEventDayIds(array $param)
    {
        $response = $this->get('internal/days/sync/ids', $param);

        return $response->data ?? [];
    }

    /**
     * @param array $param
     * @return array
     */
    public function getSyncEntryIds(array $param)
    {
        $response = $this->get('internal/races/sync/ids', $param);

        return $response->data ?? [];
    }

    /**
     * @param $id
     * @param array $attributes
     */
    public function updateDetail($id, array $attributes)
    {
        $this->put("internal/races/$id", $attributes);
    }
}
