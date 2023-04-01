<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\UserError;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Services\Actions\Admin;
use App\Services\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function __construct(protected readonly Admin $action)
    {
    }

    /**
     * This handles the updating of existing users' details
     * @param AdminRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function editUser(AdminRequest $request, string $uuid): JsonResponse
    {
        try {
            $user = $this->action->updateUserDetails($request, $uuid);
            return ApiResponse::success($user);
        } catch (UserError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
    }

    /**
     * This displays all the users that exists
     * in the system as at the time of checking
     * @param AdminRequest $request
     * @return JsonResponse
     */
    public function userListing(AdminRequest $request): JsonResponse
    {
        $users = $this->action->displayAllUsers($request);
        return ApiResponse::success($users);
    }

    /**
     * This handles the soft deleting of existing users
     *
     * @param string $uuid
     * @return JsonResponse
     */
    public function deleteUser(string $uuid): JsonResponse
    {
        try {
            $this->action->deleteUser($uuid);
        } catch (UserError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
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
        $this->action->logAdminOut();
        return ApiResponse::success();
    }
}
