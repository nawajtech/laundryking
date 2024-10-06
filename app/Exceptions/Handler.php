<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            return false;
        });
    }

    /**
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    // public function render($request, Throwable $e)
    // {
    //     //Api Unauthenticated Response
    //     if ($e instanceof AuthenticationException) {
    //         if ($request->is('api/*')) {
    //             return response()->json([
    //                 'status' => 'Unauthenticated',
    //                 'message' => 'Opps! Your session is expired. Please login again to continue.'
    //             ], 401);
    //         }
    //     }

    //     //Api Exception Response
    //     if ($request->is('api/*')) {
    //         return response()->json([
    //             'status' => 'Error',
    //             'message' => $e->getMessage() //'Something went wrong'
    //         ], 404);
    //     }

    //     return parent::render($request, $e);
    // }
}
