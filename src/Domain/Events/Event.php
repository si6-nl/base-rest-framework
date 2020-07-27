<?php

namespace Si6\Base\Domain\Events;

use ReflectionClass;
use ReflectionException;

abstract class Event
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Event constructor.
     *
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->name = (new ReflectionClass(get_called_class()))->getShortName();
    }

    /**
     * @return array
     */
    abstract public function getParameters(): array;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
