<?php

namespace App\Exceptions;

use App\Utilities\Messages;
use App\Utilities\ResponseHandler;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Database\QueryException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // You can log or report exceptions to external services here
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if (env('APP_DEBUG')) {
            return parent::render($request, $e);
        }

        // Default status and message
        $statusCode = 500;
        $responseMessage = Messages::$EXCEPTION_HANDLER_MSG;

        if ($e instanceof HttpResponseException) {
            $statusCode = $e->getStatusCode();
            $responseMessage = $e->getMessage();
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $statusCode = 405;
            $responseMessage = Messages::$ROUTE_NOT_FOUND_MSG;
        } elseif ($e instanceof NotFoundHttpException) {
            $statusCode = 404;
            $responseMessage = "Route Not Found";
        } elseif ($e instanceof AuthorizationException) {
            $statusCode = 403;
            $responseMessage = $e->getMessage();
        } elseif ($e instanceof ValidationException) {
            $statusCode = 422;
            $responseMessage = $e->validator->errors()->first();
        } elseif ($e instanceof QueryException) {
            $statusCode = 500;
            $responseMessage = Messages::$EXCEPTION_HANDLER_MSG;
        } elseif ($e instanceof \PDOException) {
            $statusCode = 500;
            $responseMessage = Messages::$EXCEPTION_HANDLER_MSG;
        }

        // Log the exception details if needed
        if (!in_array(get_class($e), $this->dontReport)) {
            $this->report($e);
        }

        return response()->json(
            ResponseHandler::ErrorResponse($responseMessage, $statusCode),
            $statusCode
        );
    }
}
