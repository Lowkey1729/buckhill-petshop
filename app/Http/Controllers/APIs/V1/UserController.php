<?php

namespace App\Http\Controllers\APIs\V1;

use App\Exceptions\OrderError;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\Actions\User;
use App\Services\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(protected readonly User $action)
    {
    }

    /**
     * This handles the display of the authenticated
     * user's details.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function viewUser(UserRequest $request): JsonResponse
    {
        $user = $this->action->viewUserDetails($request);
        return ApiResponse::success($user);
    }


    /**
     * This displays all the current orders that belongs to
     * the authenticated user.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function orders(UserRequest $request): JsonResponse
    {
        try {
            $orders = $this->action->viewOrders($request);
            return ApiResponse::success(['orders' => $orders]);
        } catch (OrderError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
    }


    /**
     * This handles the updating of existing users' details
     * @param UserRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function editUser(UserRequest $request, string $uuid): JsonResponse
    {
        try {
            $user = $this->action->updateUserDetails($request, $uuid);
            return ApiResponse::success($user);
        } catch (\App\Exceptions\UserError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function logout(UserRequest $request): JsonResponse
    {
        $this->action->logoutUser($request);
        return ApiResponse::success();
    }

    /**
     * This handles the soft removal of an existing user
     * from the system,
     * @param UserRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function deleteUser(UserRequest $request, string $uuid): JsonResponse
    {
        try {
            $this->action->softDeleteUser($request, $uuid);
        } catch (\App\Exceptions\UserError $exception) {
            return ApiResponse::failed(
                $exception->getMessage(),
                httpStatusCode: $exception->getCode()
            );
        }
        return ApiResponse::success();
    }
}
