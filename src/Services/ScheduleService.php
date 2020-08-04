<?php

namespace Si6\Base\Services;

class ScheduleService extends Microservices
{
    use SingletonInstance;

    protected function getHost()
    {
        return config('microservices.host.schedule');
    }

    /**
     * @param array $events
     */
    public function fireEvents(array $events)
    {
        $this->disableSyncException()->post('schedules/events', ['events' => $events]);
    }
}
