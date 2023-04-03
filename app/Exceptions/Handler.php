<?php

namespace App\Exceptions;

use App\Services\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response
    {
        return $this->shouldReturnJson($request, $e)
            ? $this->handleJsonResponses($e)
            : $this->prepareResponse($request, $e);
    }

    /**
     * @param Throwable $e
     * @return Response
     */
    protected function handleJsonResponses(Throwable $e): Response
    {
        return match (true) {
            $e instanceof MethodNotAllowedHttpException => $this->httpResponseException($e->getMessage(), 405),

            $e instanceof NotFoundHttpException => $this->httpResponseException('Api resource not found', httpStatusCode: 404),

            $e instanceof InvalidSignatureException => $this->httpResponseException('Invalid signature', httpStatusCode: 402),

            $e instanceof AuthenticationException => $this->httpResponseException('Not authenticated', httpStatusCode: 401),

            default => $this->httpResponseException('An unexpected error was encountered. Please, contact support', 500)
        };
    }


    protected function httpResponseException(
        string $message,
        int $httpStatusCode
    ): JsonResponse {
        return
            ApiResponse::failed(
                $message,
                httpStatusCode: $httpStatusCode
            );
    }
}
