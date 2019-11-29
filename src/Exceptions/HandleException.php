<?php

namespace Si6\Base\Traits;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Si6\Base\Exceptions\BaseException;
use Si6\Base\Exceptions\MicroservicesException;
use Si6\Base\Exceptions\PlatformException;
use Si6\Base\Http\ResponseTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HandleException
{
    use ResponseTrait;

    protected function handleValidationMessage($message)
    {
        $message = Str::upper($message);
        $message = rtrim($message, '.');
        $message = str_replace(' ', '_', $message);

        return $message;
    }

    protected function handleHttpExceptionMessage($exception)
    {
        $message = class_basename($exception);
        $message = Str::snake($message);
        $message = Str::upper($message);

        return $message;
    }

    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            $this->handleValidation($exception);
        }

        if ($exception instanceof HttpException) {
            $this->handleHttp($exception);
        }

        if ($exception instanceof BaseException) {
            $this->handleBase($exception);
        }

        if ($exception instanceof AuthenticationException) {
            $this->handleAuth($exception);
        }

        if (!$this->errors) {
            $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->addError(null, 'INTERNAL_SERVER_ERROR');
        }

        if (app()->environment(['local', 'dev']) && env('APP_DEBUG') == true) {
            $this->setDebug($exception);
        }
    }

    public function handleExceptionReport(Exception $exception)
    {
        if ($exception instanceof PlatformException) {
            return $this->handlePlatformExceptionReport($exception);
        }

        if ($exception instanceof MicroservicesException) {
            return $this->handleMicroservicesExceptionReport($exception);
        }

        return $exception;
    }

    protected function handleValidation(ValidationException $exception)
    {
        foreach ($exception->errors() as $field => $errors) {
            foreach ($errors as $error) {
                $this->addError($field, $this->handleValidationMessage($error));
            }
        }
        $this->setStatusCode($exception->status);
    }

    protected function handleHttp(HttpException $exception)
    {
        $this->setStatusCode($exception->getStatusCode())
            ->addError(null, $this->handleHttpExceptionMessage($exception))
            ->setHeaders($exception->getHeaders());
    }

    protected function handleBase(BaseException $exception)
    {
        if ($exception instanceof MicroservicesException) {
            $this->setErrors($exception->errors());
        } else {
            $field = null;
            if ($exception->getStatusCode() === Response::HTTP_UNPROCESSABLE_ENTITY) {
                $field = $exception->getField();
            }

            $this->addError($field, $exception->getMessage());
        }
        $this->setStatusCode($exception->getStatusCode());
    }

    protected function handleAuth(AuthenticationException $exception)
    {
        $this->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->addError(null, 'UNAUTHENTICATED');
    }

    protected function handlePlatformExceptionReport(PlatformException $exception)
    {
        $exception->request = [
            'remote-address'    => request()->fullUrl(),
            'params'            => request()->all(),
        ];

        return $exception;
    }

    protected function handleMicroservicesExceptionReport(MicroservicesException $exception)
    {
        $exception->request = [
            'remote-address'    => request()->fullUrl(),
            'params'            => request()->all(),
        ];

        return $exception;
    }
}
