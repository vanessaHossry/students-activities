<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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

    // --- for error handling
    public function render($request, Throwable $exception)
    {
        //-- methd not allowed
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException){
            return $this->errorResponse("METHOD NOT ALLOWED", Response::HTTP_METHOD_NOT_ALLOWED);
        }
        if($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException){
            return $this->errorResponse("user does not have the right permissions", Response::HTTP_FORBIDDEN);
        }
        if($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException){
            return $this->errorResponse("unauthorized", Response::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $exception);
    }
}
