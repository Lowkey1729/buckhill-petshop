<?php

namespace App\Http\Controllers\APIs\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\User;
use App\Services\Helpers\ApiResponse;
use App\Services\ModelFilters\UserFilters\FilterUser;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
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
