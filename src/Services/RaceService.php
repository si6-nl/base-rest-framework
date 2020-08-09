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
        $response = $this->internal()->get("races/detail", $param);

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
     * @param $id
     * @param array $attributes
     */
    public function updateDetail($id, array $attributes)
    {
        $this->put("internal/races/$id", $attributes);
    }
}
