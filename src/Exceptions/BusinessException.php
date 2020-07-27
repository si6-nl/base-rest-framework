<?php

namespace Si6\Base\Exceptions;

/**
 * Class BusinessException
 *
 * @package Si6\Base\Exceptions
 */
class BusinessException extends BaseException
{
    /**
     * @var int
     */
    protected $statusCode = 400;

    /**
     * @var string
     */
    private $userMessage;

    public function __construct(string $userMessage = '')
    {
        $this->userMessage = $userMessage;
        parent::__construct($this->message);
    }

    /**
     * @return string
     */
    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}