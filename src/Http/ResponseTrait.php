<?php

namespace Si6\Base\Http;

use Exception;
use Illuminate\Http\Response;
use Si6\Base\Exceptions\PlatformException;

trait ResponseTrait
{
    protected $data = [];

    protected $included = [];

    protected $headers = [];

    protected $statusCode = 200;

    protected $errors = [];

    protected $debug = null;

    protected $dev = null;

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function addData(string $key, $data)
    {
        $this->data[$key] = $data;

        return $this;
    }

    public function addIncluded(string $key, $data)
    {
        $this->included[$key] = $data;

        return $this;
    }

    public function setIncluded($included)
    {
        $this->included = $included;

        return $this;
    }

    public function addDevData(string $key, $data)
    {
        $this->dev[$key] = $data;

        return $this;
    }

    public function addArrayData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->addData($key, $value);
        }

        return $this;
    }

    protected function setDebug(Exception $exception)
    {
        $this->debug = [
            'message' => $exception->getMessage(),
            'code'    => $exception->getCode(),
            'file'    => $exception->getFile(),
            'line'    => $exception->getLine(),
            'trace'   => $exception->getTraceAsString(),
        ];

        if ($exception instanceof PlatformException) {
            $this->debug['request'] = request()->all();
        }

    }

    protected function handleMessage($message)
    {
        // You can override this function to custom message
        return $message;
    }

    public function addError($key, $message)
    {
        $message = $this->handleMessage($message);

        $error['message'] = $message;

        if ($key) {
            $error['field'] = $key;
        }

        $this->errors[] = $error;

        return $this;
    }

    public function addErrors($errors)
    {
        foreach ($errors as $key => $value) {
            $this->addError($key, $value);
        }

        return $this;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    public function setHeader($key, $header)
    {
        $this->headers[$key] = $header;

        return $this;
    }

    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }

        return $this;
    }

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;

        return $this;
    }

    public function success($data, $statusCode = 200)
    {
        $this->setStatusCode($statusCode)
            ->setData($data);

        return $this->getResponse();
    }

    /**
     * @param $error
     * @return $this
     */
    public function addCustomError($error)
    {
        $this->errors[] = $error;

        return $this;
    }

    public function error($message, $statusCode = 500)
    {
        $this->setStatusCode($statusCode)
            ->addError(null, $message);

        return $this->getResponse();
    }

    public function getResponse()
    {
        $response = [];

        if (!empty($this->errors)) {
            // Update default status if it's not set to error
            if ($this->statusCode == Response::HTTP_OK) {
                $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            $response['errors'] = $this->errors;
        } else {
            $response['data'] = $this->data;
            if (!empty($this->included)) {
                $response['included'] = $this->included;
            }
        }

        if (app()->environment(['local', 'dev'])) {
            if ($this->debug) {
                $response['debug'] = $this->debug;
            }
            if ($this->dev) {
                $response['dev'] = $this->dev;
            }
        }

        return response()->json($response, $this->statusCode, $this->headers);
    }
}
