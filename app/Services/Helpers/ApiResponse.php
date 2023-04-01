<?php

namespace App\Services\Helpers;

use App\Services\Enums\ApiResponse as ApiResponseEnum;
use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(
        array|object $data = [],
        array $extraData = [],
        int $httpStatusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => ApiResponseEnum::success()->value,
            'data' => $data,
            'error' => null,
            'errors' => [],
            'extra' => $extraData,

        ], $httpStatusCode);
    }

    public static function failed(
        string $errorMessage,
        array $data = [],
        array $errors = [],
        array $errorTrace = [],
        int $httpStatusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => ApiResponseEnum::failed()->value,
            'data' => $data,
            'error' => $errorMessage,
            'errors' => $errors,
            'trace' => $errorTrace,
        ], $httpStatusCode);
    }
}
