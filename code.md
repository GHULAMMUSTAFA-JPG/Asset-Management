so a fellow said we should do this 

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
        api: [__DIR__.'/../routes/api.php,
        __DIR__.'/../routes/auth.php
],
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->report(function (Throwable $e) {
            try {
                $request = request();

                $status = method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : 500;

                $errorData = [
                    'message'      => $e->getMessage(),
                    'type'         => get_class($e),
                    'severity'     => $status >= 500 ? 'critical' : 'error',
                    'file'         => $e->getFile(),
                    'line'         => $e->getLine(),
                    'stack_trace'  => $e->getTraceAsString(),
                    'url'          => $request->fullUrl(),
                    'method'       => $request->method(),
                    'request_body' => json_encode($request->except(['password', 'token'])),
                    'ip_address'   => $request->ip(),
                    'user_id'      => null,
                    'status_code'  => $status,
                ];

                \App\Models\ErrorLog::create($errorData);

                $payload = json_encode([
                    'type' => 'error',
                    'data' => $errorData,
                ]);

                $socket = @stream_socket_client(
                    'tcp://127.0.0.1:8080',
                    $errno,
                    $errstr,
                    1
                );

                if ($socket) {
                    fwrite($socket, $payload);
                    fclose($socket);
                }

            } catch (\Throwable $loggingException) {
                // silent fail
            }
        });

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

rather than using routes/api.php  for 

<?php

require __DIR__ . '/auth.php';

