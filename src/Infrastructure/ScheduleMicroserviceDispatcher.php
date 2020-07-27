<?php

namespace Si6\Base\Infrastructure;

use Si6\Base\DataTransferObjects\FireEventDTO;
use Si6\Base\Domain\Events\Event;
use Si6\Base\Services\ScheduleService;

/**
 * Class ScheduleMicroserviceDispatcher
 *
 * @package Si6\Base\Infrastructure
 */
final class ScheduleMicroserviceDispatcher implements MicroserviceDispatcher
{
    /**
     * @var ScheduleService
     */
    private $service;

    /**
     * ScheduleMicroserviceDispatcher constructor.
     *
     * @param ScheduleService $service
     */
    public function __construct(ScheduleService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Event[] $events
     */
    public function dispatch(array $events)
    {
        $fireEvent = function ($event) {
            $dto = new FireEventDTO($event->getName(), $event->getParameters());

            return $dto->getParameters();
        };
        $this->service->fireEvents(array_map($fireEvent, $events));
    }
}
