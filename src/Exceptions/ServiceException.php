<?php

namespace Si6\Base\Exceptions;

final class ServiceException extends \RuntimeException
{
    /**
     * @var string
     */
    private $userMessage;

    public function __construct(string $userMessage)
    {
        $this->userMessage = $userMessage;
        parent::__construct('Service exception');
    }

    /**
     * @return string
     */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}