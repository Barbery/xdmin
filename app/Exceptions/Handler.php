<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Overtrue\Socialite\InvalidStateException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \App\Exceptions\Error::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        // return parent::render($request, $e);
        $msg        = $e->getMessage();
        $data       = [];
        $code       = 1;
        $statusCode = 500;
        switch ($e) {
            case $e instanceof ValidationException:
                $data       = $e->validator->errors();
                $msg        = implode('', $e->validator->messages()->all());
                $statusCode = 422;
                break;

            case $e instanceof Error:
                $data = $e->getData();
                $code = $e->getCode();
                break;

            case $e instanceof TokenExpiredException:
            case $e instanceof AuthenticationException:
            case $e instanceof TokenBlacklistedException:
                $code = 1002;
                break;

            case $e instanceof InvalidStateException:
                $statusCode = 401;
                $code       = 1004;
                break;

            case $e instanceof ModelNotFoundException:
            case $e instanceof InvalidArgumentException:
                $statusCode = 404;
                break;
        }

        $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : $statusCode;
        $msg        = empty($msg) ? (Response::$statusTexts[$statusCode] ?? '') : $msg;
        $httpCode   = $statusCode;
        if (empty($_SERVER['HTTP_ORIGIN']) && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $httpCode = 200;
        }

        return response(json_encode([
            'code'        => $code,
            'msg'         => $msg,
            'data'        => $data,
            'status_code' => $statusCode,
        ], JSON_UNESCAPED_UNICODE), $httpCode)->header('Content-Type', 'application/json');
    }
}
