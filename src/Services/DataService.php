<?php

namespace Si6\Base\Services;

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
}
