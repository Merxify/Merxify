<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    /**
     * @param  array<string|mixed>  $data
     */
    protected function ok(string $message, array $data = []): JsonResponse
    {
        return $this->success($message, $data);
    }

    /**
     * @param  array<string|mixed>  $data
     */
    protected function success(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    protected function error(mixed $errors = [], int $statusCode = 0): JsonResponse
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $statusCode,
            ], $statusCode);
        }

        return response()->json([
            'errors' => $errors,
        ]);
    }

    protected function notAuthorized(string $message): JsonResponse
    {
        return $this->error([
            'message' => $message,
            'status' => 401,
            'source' => '',
        ]);
    }
}
