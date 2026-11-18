<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data, int $status = 200): JsonResponse
    {
        return response()->json(['data' => $data], $status);
    }

    public static function error(string $message, int $status, array $errors = []): JsonResponse
    {
        $body = ['message' => $message];

        if ($errors !== []) {
            $body['errors'] = $errors;
        }

        return response()->json($body, $status);
    }
}
