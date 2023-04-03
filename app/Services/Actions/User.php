<?php

namespace App\Services\Actions;

use App\Exceptions\OrderError;
use App\Exceptions\UserError;
use App\Http\Requests\UserRequest;
use App\Models\Order;
use App\Models\User as UserModel;
use Illuminate\Pagination\LengthAwarePaginator;

class User
{
    public function viewUserDetails(UserRequest $request): object
    {
        return (object) $request->user();
    }

    /**
     * @param UserRequest $request
     * @return LengthAwarePaginator<Order>|null
     * @throws OrderError
     */
    public function viewOrders(UserRequest $request): LengthAwarePaginator|null
    {
        $user = $request->user()?->load(['orders']);
        $orders = $user?->orders()->with(['payment', 'orderStatus']);
        if ($orders?->count() === 0) {
            throw new OrderError('You have no orders', 404);
        }
        return $orders?->paginate(10);
    }

    /**
     * @param UserRequest $request
     * @param string $uuid
     * @return UserModel
     * @throws UserError
     */
    public function updateUserDetails(UserRequest $request, string $uuid): UserModel
    {
        $user = UserModel::whereUuid($uuid)->first();
        if (!$user) {
            throw new UserError('User not found', 404);
        }
        $user->update(
            array_filter(
                $request->all(),
                function ($x) {
                    return !is_null($x);
                }
            )
        );
        return $user;
    }

    /**
     * @param UserRequest $request
     * @return void
     */
    public function logoutUser(UserRequest $request): void
    {
        $request->user()?->deleteAccessToken();
    }

    /**
     * @param UserRequest $request
     * @param string $uuid
     * @return void
     * @throws UserError
     */
    public function softDeleteUser(UserRequest $request, string $uuid): void
    {
        $user = UserModel::whereUuid($uuid)->first();
        if (!$user) {
            throw new UserError('User not found', 404);
        }
        $user->delete();
        request()->user()?->deleteAccessToken();
    }
}
