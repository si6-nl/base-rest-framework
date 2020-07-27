<?php

namespace Si6\Base\DataTransferObjects;

class FireEventDTO
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $param;

    public function __construct(string $name, array $param)
    {
        $this->name  = $name;
        $this->param = $param;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getParam(): array
    {
        return $this->param;
    }

    public function getParameters(): array
    {
        return [
            'name'  => $this->name,
            'param' => $this->param,
        ];
    }
}
