<?php

namespace Si6\Base\Infrastructure;

interface MicroserviceDispatcher
{
    /**
     * @param array $events
     */
    public function dispatch(array $events);
}
