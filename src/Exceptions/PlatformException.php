<?php

namespace Si6\Base\Exceptions;

use Si6\Base\Enums\PlatformResultCode;

class PlatformException extends BaseException
{
    protected $message = 'PLATFORM_REQUEST_ERROR';

    public function __construct($data, $statusCode = null, $message = '', $code = 0)
    {
        parent::__construct();
        $this->statusCode = $statusCode ?: $this->statusCode;
        $this->message    = $message ?: $this->message;
        $this->code       = $code ?: $this->code;

        if (PlatformResultCode::hasValue($this->code)) {
            $this->message = (string)PlatformResultCode::getKey($this->code);
        }
    }
}
