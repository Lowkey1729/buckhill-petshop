<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\User;
use App\Services\Helpers\ApiResponse;
use App\Services\ModelFilters\UserFilters\FilterUser;
use App\Services\Traits\Auth\Login as LoginTrait;
use App\Services\Traits\Auth\Register as RegisterTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    use LoginTrait;
    use RegisterTrait;

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
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            return ApiResponse::failed("User not found", httpStatusCode: 404);
        }
        $user->update(
            array_filter(
                $request->all(),
                function ($x) {
                    return !is_null($x);
                }
            )
        );
        return ApiResponse::success($user);
    }

    public function userListing(AdminRequest $request): JsonResponse
    {
        $data = array_filter($request->all(), 'strlen');
        $users = FilterUser::apply($data)
            ->latest()
            ->paginate($request->limit ?? 10);

        return ApiResponse::success($users);
    }

    public function deleteUser(AdminRequest $request, string $uuid): JsonResponse
    {
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            return ApiResponse::failed("User not found", httpStatusCode: 404);
        }
        $user->delete();
        return ApiResponse::success();
    }

    public function logout(): JsonResponse
    {
        request()->user()?->deleteAccessToken();
        return ApiResponse::success();
    }
}
