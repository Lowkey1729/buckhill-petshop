<?php

namespace App\Services\Traits\Auth;

use App\Models\User;

trait Register
{
    /**
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::query()->create($this->getData($data));
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getData(array $data): array
    {
        return [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
            'address' => $data['address'],
            'is_marketing' => isset($data['is_marketing']) ? 1 : 0,
            'avatar' => $data['avatar'] ?? null,
            'email_verified_at' => now()
        ];
    }
}
