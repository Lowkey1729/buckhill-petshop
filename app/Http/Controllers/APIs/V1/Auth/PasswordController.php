<?php

namespace App\Http\Controllers\APIs\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordRequest;
use App\Services\Actions\Auth\Password;
use App\Services\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class PasswordController extends Controller
{
    public function __construct(protected readonly Password $action)
    {
    }

    /**
     * @param PasswordRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(PasswordRequest $request): JsonResponse
    {
        try {
            $token = $this->action->forgotUserPassword($request);
            return ApiResponse::success(['reset_token' => $token]);
        } catch (\App\Exceptions\PasswordError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
    }

    /**
     * @param PasswordRequest $request
     * @return JsonResponse
     */
    public function resetPasswordToken(PasswordRequest $request): JsonResponse
    {
        $passwordReset = $this->action->resetUserPasswordToken($request);
        if (!$passwordReset['status']) {
            return ApiResponse::failed($passwordReset['message']);
        }
        return ApiResponse::success();
    }
}
