<?php

namespace App\Traits;

use App\Models\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

trait ApiResponses
{
    /**
     * @param  array<string|mixed>  $data
     */
    protected function ok(string $message, array $data = []): JsonResponse
    {
        return $this->success($data, $message);
    }

    /**
     * @param  Cart|array<string, mixed>|null  $data
     */
    protected function success(
        array|Cart|null $data = null,
        ?string $message = null,
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode,
        ], $statusCode);
    }

    /**
     * @param  MessageBag|array<string|mixed>  $errors
     */
    protected function error(string $message, array|MessageBag|null $errors, int $statusCode = 422): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    protected function notAuthorized(string $message): JsonResponse
    {
        return $this->error(
            $message,
            [
                'status' => 401,
            ], 401);
    }
}
