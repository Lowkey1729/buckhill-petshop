<?php

namespace App\Services\Actions;

use App\Exceptions\UserError;
use App\Http\Requests\AdminRequest;
use App\Models\User;
use App\Services\ModelFilters\UserFilters\FilterUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class Admin
{
    /**
     * @param AdminRequest $request
     * @param string $uuid
     * @return User
     * @throws UserError
     */
    public function updateUserDetails(AdminRequest $request, string $uuid): User
    {
        $data = $request->all();
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            throw new UserError('User not found.', 404);
        }
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update(
            array_filter(
                $data,
                function ($x) {
                    return !is_null($x);
                }
            )
        );
        return $user;
    }

    /**
     * @param AdminRequest $request
     * @return LengthAwarePaginator<Model>
     */
    public function displayAllUsers(AdminRequest $request): LengthAwarePaginator
    {
        $data = array_filter($request->all(), 'strlen');
        return FilterUser::apply($data)
            ->latest()
            ->paginate($request->limit ?? 10);
    }

    /**
     * @param string $uuid
     * @return void
     * @throws UserError
     */
    public function deleteUser(string $uuid): void
    {
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            throw new UserError('User not found.', 404);
        }
        $user->delete();
        request()->user()?->deleteAccessToken();
    }

    public function logAdminOut(): void
    {
        request()->user()?->deleteAccessToken();
    }
}
