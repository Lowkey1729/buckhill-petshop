<?php

namespace App\Services\Traits\Auth;

use App\Models\User;
use App\Services\Enums\UserType;

trait Register
{
    public function createUser(array $data): User
    {
        return User::query()->create($this->getData($data));
    }

    protected function getData(array $data): array
    {
        return [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
            'is_admin' => UserType::admin()->value,
            'address' => $data['address'],
            'is_marketing' => isset($data['is_marketing']) ? 1 : 0,
            'avatar' => $data['avatar'] ?? null,
        ];
    }
}
