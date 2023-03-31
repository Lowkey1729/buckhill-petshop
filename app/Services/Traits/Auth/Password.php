<?php

namespace App\Services\Traits\Auth;

use App\Services\Enums\ApiResponse as ApiResponseEnum;
use Illuminate\Support\Facades\Password as PasswordReset;

trait Password
{
    protected function passwordResetStatus(string $response): array
    {
        return match ($response) {
            PasswordReset::PASSWORD_RESET => [
                'message' => 'Password reset successful',
                'status' => ApiResponseEnum::success()->value,
            ],
            PasswordReset::INVALID_TOKEN => [
                'message' => 'Invalid Token',
                'status' => ApiResponseEnum::failed()->value,
            ],
            default => [
                'message' => 'Invalid User',
                'status' => ApiResponseEnum::failed()->value,
            ],
        };
    }
}
