<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Services\Helpers\ApiResponse;
use App\Services\Traits\Auth\Login as LoginTrait;
use App\Services\Traits\Auth\Register as RegisterTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    use LoginTrait, RegisterTrait;

    public function login(AdminRequest $request): JsonResponse
    {
        $data = $request->all();
        $this->failedAuthentication($data);

        if (! $this->user->is_admin) {
            return ApiResponse::failed(
                'You do not have the permission to access this resource',
                httpStatusCode: 403
            );
        }

        try {
            $token = $this->user->createToken(sprintf('%s token', $this->user->email));
        } catch (\Exception $exception) {
            Log::error($exception);

            return ApiResponse::failed('An unexpected error was encountered.', httpStatusCode: 500);
        }

        return ApiResponse::success(['token' => $token->getPlainTextToken()]);
    }

    public function createAdmin(AdminRequest $request): JsonResponse
    {
        $data = $request->all();
        $user = $this->createUser($data);
        try {
            $token = $user->createToken(sprintf('%s token', $user->email));
        } catch (\Exception $exception) {
            Log::error($exception);

            return ApiResponse::failed('An unexpected error was encountered.', httpStatusCode: 500);
        }
        $user['token'] = $token->getPlainTextToken();

        return ApiResponse::success($user);
    }

    public function editUser(AdminRequest $request, string $uuid): JsonResponse
    {
        return ApiResponse::success();
    }

    public function userListing(AdminRequest $request): JsonResponse
    {
        return ApiResponse::success();
    }

    public function deleteUser(AdminRequest $request): JsonResponse
    {
        return ApiResponse::success();
    }

    public function logout(): JsonResponse
    {
        return ApiResponse::success();
    }
}
