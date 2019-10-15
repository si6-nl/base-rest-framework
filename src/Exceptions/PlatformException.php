<?php

namespace Si6\Base\Exceptions;

class PlatformException extends BaseException
{
    protected $message = 'PLATFORM_REQUEST_ERROR';

    public function __construct($data, $statusCode = null, $message = '')
    {
        parent::__construct();
        $this->statusCode = $statusCode ?: $this->statusCode;
        $this->message    = $message ?: $this->message;
    }
}
