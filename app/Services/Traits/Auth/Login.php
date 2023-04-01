<?php

namespace App\Services\Traits\Auth;

use App\Models\User;
use App\Services\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

trait Login
{
    protected User|null $user;

    /**
     * This validates the user/admin authentication request
     * @param array $data
     * @return void
     */
    protected function failedAuthentication(array $data): void
    {
        $user = User::query()->where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            $this->httpResponseException('The provided credentials are incorrect.');
        }

        if (!$user?->hasVerifiedEmail()) {
            $this->httpResponseException('Email has not been verified.');
        }
        match (true) {
            \request()->routeIs('admin.login') => $this->allowOnlyAdmins($user),
            default => null
        };
        $this->user = $user;
    }

    protected function allowOnlyAdmins(User|null $user): void
    {
        if (!$user?->is_admin) {
            $this->httpResponseException('You do not have the permission to access this resource');
        }
    }

    protected function httpResponseException(string $message): void
    {
        throw new HttpResponseException(
            ApiResponse::failed(
                $message,
                httpStatusCode: 403
            )
        );
    }

    protected function getUser(): User|null
    {
        return $this->user;
    }
}
