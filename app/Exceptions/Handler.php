<?php

namespace App\Exceptions;

use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
        DomainException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     *
     * @return void
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {   
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];

            return $this->errorResponse($this->defaultResponse($message), $code);
        }
        if ($exception instanceof ModelNotFoundException) {
            $message = $this->parseModelName($exception->getModel()) . " no existe";
            return $this->errorResponse($this->defaultResponse($message), 404);
        }
        if ($exception instanceof DomainException) {
            $message = $exception->getMessage();
            return $this->errorResponse($this->defaultResponse($message), 400);
        }
        if ($exception instanceof UnauthorizedException) {
            return $this->errorResponse(
                $this->defaultResponse('unauthorized'),
                403
            );
        }

        if (env('APP_DEBUG', false)) {
           return parent::render($request, $exception);
        }

        return $this->errorResponse($this->defaultResponse('Unexpected error. Try later'), Response::HTTP_INTERNAL_SERVER_ERROR);
      
    }
}