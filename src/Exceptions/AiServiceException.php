<?php

namespace Si6\Base\Exceptions;

use Illuminate\Support\Facades\Log;

class AiServiceException extends BaseException
{
    protected $message = 'AI_SERVICE_REQUEST_ERROR';

    public function __construct($data, $statusCode = null, $message = '', $code = 0)
    {
        parent::__construct();

        $this->statusCode = $statusCode ?: $this->statusCode;
        $this->code       = $code ?: $this->code;
        $this->message    = ($message ?: $this->message) . '_CODE_' . $this->code;

        Log::info('AI_SERVICE_REQUEST_ERROR: ', $data ?: []);
    }
}
