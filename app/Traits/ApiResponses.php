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

    /**
     * @param  array<string|mixed>  $errors
     */
    protected function error(array $errors, int $statusCode = 0): JsonResponse
    {
        return response()->json([
            'errors' => $errors,
        ], $statusCode);
    }

    protected function notAuthorized(string $message): JsonResponse
    {
        return $this->error([
            'message' => $message,
            'status' => 401,
        ], 401);
    }
}
