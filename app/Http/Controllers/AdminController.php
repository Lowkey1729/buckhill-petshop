<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminRequest;
use App\Models\User;
use App\Services\Enums\UserType;
use App\Services\Helpers\ApiResponse;
use App\Services\Traits\Auth\Login as LoginTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    use LoginTrait;

    public function login(AdminRequest $request): JsonResponse
    {
        $data = $request->all();
        $this->failedAuthentication($data);

        if (!$this->user->is_admin) {
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
        User::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
            'is_admin' => UserType::admin()->value,
            'address' => $data['address'],
            'is_marketing' => $data['is_marketing'],
        ]);

        return ApiResponse::success();
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
