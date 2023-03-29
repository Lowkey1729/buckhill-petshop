<?php

namespace App\Services\Traits\Auth;

use App\Models\User;
use App\Services\Helpers\ApiResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

trait Login
{
    protected User $user;

    /**
     * This validates the user/admin authentication request
     */
    protected function failedAuthentication(array $data): void
    {
        $user = User::query()->where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(
                ApiResponse::failed(
                    'The provided credentials are incorrect.',
                    httpStatusCode: 401
                )
            );
        }

        if (! $user->hasVerifiedEmail()) {
            throw new HttpResponseException(
                ApiResponse::failed(
                    'Email has not been verified.',
                    ['is_verified' => false],
                    httpStatusCode: 401
                )
            );
        }

        $this->setUser($user);
    }

    protected function setUser(User $user): void
    {
        $this->user = $user;
    }
}
