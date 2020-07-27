<?php

namespace Si6\Base\Services;

use Si6\Base\DataTransferObjects\FireEventDTO;

class ScheduleService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.schedule');
    }

    /**
     * @param FireEventDTO[] $events
     */
    public function fireEvents(array $events)
    {
        $this->post('schedules/events', ['events' => $events]);
    }
}
