<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
   ->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
   
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    $exceptions->render(function (Throwable $e, Request $request) {

        if ($e instanceof ValidationException) {
            return response()->validationError($e->errors());
        }

        if ($e instanceof AuthenticationException) {
            return response()->unauthorized();
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->notFound();
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->error('Method not allowed', 405);
        }

        $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

        return response()->error(
            app()->environment('production') ? 'Server Error' : $e->getMessage(),
            $status
        );
    });

})
    ->create();