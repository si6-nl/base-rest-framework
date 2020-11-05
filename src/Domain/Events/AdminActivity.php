<?php

namespace Si6\Base\Domain\Events;

use ReflectionException;

class AdminActivity extends Event
{
    /**
     * @var int
     */
    private $userId;
    /**
     * @var string
     */
    private $method;
    /**
     * @var string
     */
    private $path;
    private $data;

    /**
     * AdminActivity constructor.
     *
     * @param int $userId
     * @param string $method
     * @param string $path
     * @param $data
     * @throws ReflectionException
     */
    public function __construct(int $userId, string $method, string $path, $data)
    {
        parent::__construct();
        $this->userId = $userId;
        $this->method = $method;
        $this->path   = $path;
        $this->data   = $data;
    }

    public function getParameters(): array
    {
        return [
            'user_id' => $this->userId,
            'method'  => $this->method,
            'path'    => $this->path,
            'data'    => $this->data,
        ];
    }
}
