<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
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

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Api endpoint does not exist',
                    'code' => 404
                ], 404);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 405,
                    'message' => 'Method Not Allowed.' . $e->getMessage(),
                ], 405);
            }
        });
        
        $this->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() == 419) {
                return redirect()
                  ->back()
                  ->withInput($request->except('_token'))
                  ->withMessage('You page session expired. Please try again');
        
            }
        });
        
        // if ($e instanceof \Illuminate\Session\TokenMismatchException){ // <<<=========== the Code
        //     if ($request->expectsJson()) {
        //         return response()->json(['error' => 'Unauthenticated.'], 401);
        //     }
    
        //     return redirect('/login')->with('message', 'You page session expired. Please try again');
        // }
        
    }
    
    
    

    /**
     * @param $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $apiQueryString = $request->getRequestUri();
        $apiExplode = explode('/', $apiQueryString);
        \array_splice($apiExplode, 0, 1);
        if (is_array($apiExplode) && $apiExplode[0] === 'api') {
            return response()->json([
                'success' => false,
                'code' => 403,
                'message' => 'not authorized',
            ], 403);
        }

        return parent::unauthenticated($request, $exception);
    }
}
