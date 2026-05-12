<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Response::macro('success', function (mixed $data = null, string $message = 'Success', int $status = 200) {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data'    => $data,
            ], $status);
        });

        Response::macro('created', function (mixed $data = null, string $message = 'Created successfully') {
            return Response::json([
                'success' => true,
                'message' => $message,
                'data'    => $data,
            ], 201);
        });

        Response::macro('error', function (string $message = 'Something went wrong', int $status = 400, mixed $errors = null) {
            $body = [
                'success' => false,
                'message' => $message,
            ];

            if ($errors !== null) {
                $body['errors'] = $errors;
            }

            return Response::json($body, $status);
        });

        Response::macro('notFound', function (string $message = 'Resource not found') {
            return Response::json([
                'success' => false,
                'message' => $message,
            ], 404);
        });

        Response::macro('unauthorized', function (string $message = 'Unauthenticated') {
            return Response::json([
                'success' => false,
                'message' => $message,
            ], 401);
        });

        Response::macro('forbidden', function (string $message = 'Forbidden') {
            return Response::json([
                'success' => false,
                'message' => $message,
            ], 403);
        });

        Response::macro('validationError', function (mixed $errors, string $message = 'Validation failed') {
            return Response::json([
                'success' => false,
                'message' => $message,
                'errors'  => $errors,
            ], 422);
        });
    }
}

// namespace App\Helpers;

// use Illuminate\Http\JsonResponse;

// class ApiResponse
// {
//     public static function success(mixed $data = null, string $message = 'Success', int $status = 200): JsonResponse
//     {
//         return response()->json([
//             'success' => true,
//             'message' => $message,
//             'data'    => $data,
//         ], $status);
//     }

//     public static function error(string $message = 'Error', int $status = 400, mixed $errors = null): JsonResponse
//     {
//         $response = [
//             'success' => false,
//             'message' => $message,
//         ];

//         if ($errors !== null) {
//             $response['errors'] = $errors;
//         }

//         return response()->json($response, $status);
//     }

//     public static function created(mixed $data = null, string $message = 'Created successfully'): JsonResponse
//     {
//         return self::success($data, $message, 201);
//     }

//     public static function noContent(string $message = 'Deleted successfully'): JsonResponse
//     {
//         return response()->json(['success' => true, 'message' => $message], 200);
//     }
// }