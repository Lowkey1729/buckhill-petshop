<?php

namespace App\Services\Traits\Auth;

use App\Models\User;
use App\Services\Enums\UserType;

trait Register
{
    public function createUser(array $data): User
    {
        return  User::query()->create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
            'is_admin' => UserType::admin()->value,
            'address' => $data['address'],
            'is_marketing' => is_null($data['marketing']) ? 0 : 1,
            'avatar' => $data['avatar'],
        ]);
    }
}
