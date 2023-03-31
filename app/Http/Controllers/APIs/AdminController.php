<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\User;
use App\Services\Enums\UserType;
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

    /**
     * This method handles the authentication of the user
     * @param AdminRequest $request
     * @return JsonResponse
     */
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

    /**
     * This handles the registration of new admin users
     * @param AdminRequest $request
     * @return JsonResponse
     */
    public function createAdmin(AdminRequest $request): JsonResponse
    {
        $data = $request->all();
        $user = $this->createUser($data);
        $user->update(['is_admin' => UserType::admin()->value]);
        try {
            $token = $user->createToken(sprintf('%s token', $user->email));
        } catch (\Exception $exception) {
            Log::error($exception);

            return ApiResponse::failed('An unexpected error was encountered.', httpStatusCode: 500);
        }
        $user['token'] = $token->getPlainTextToken();

        return ApiResponse::success($user);
    }

    /**
     * This handles the updating of existing users' details
     * @param AdminRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function editUser(AdminRequest $request, string $uuid): JsonResponse
    {
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            return ApiResponse::failed('User not found', httpStatusCode: 404);
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

    /**
     * This displays all the users that exists
     * in the system as at the time of checking
     * @param AdminRequest $request
     * @return JsonResponse
     */
    public function userListing(AdminRequest $request): JsonResponse
    {
        $data = array_filter($request->all(), 'strlen');
        $users = FilterUser::apply($data)
            ->latest()
            ->paginate($request->limit ?? 10);

        return ApiResponse::success($users);
    }

    /**
     * This handles the soft deleting of existing users
     * @param AdminRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function deleteUser(AdminRequest $request, string $uuid): JsonResponse
    {
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            return ApiResponse::failed('User not found', httpStatusCode: 404);
        }
        $user->delete();
        request()->user()?->deleteAccessToken();
        return ApiResponse::success();
    }

    /**
     * This handles the termination of current access token
     * A user gets thrown out of the system after the
     * token has been removed/deleted
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        request()->user()?->deleteAccessToken();
        return ApiResponse::success();
    }
}
