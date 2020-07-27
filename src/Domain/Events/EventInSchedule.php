<?php

namespace Si6\Base\Domain\Events;

abstract class EventInSchedule
{
    /**
     * @var array
     */
    protected $param;

    public function __construct(array $param)
    {
        $this->param = $param;
    }

    /**
     * @return array
     */
    public function getParam(): array
    {
        return $this->param;
    }
}
