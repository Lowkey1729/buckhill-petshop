<?php

namespace App\Services\Actions\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\Enums\UserType;
use App\Services\Concerns\Auth\Register as RegisterTrait;
use Exception;

class Register
{
    use RegisterTrait;

    /**
     * @param RegisterRequest $request
     * @return User
     * @throws Exception
     */
    public function registerUser(RegisterRequest $request): User
    {
        $data = $request->all();
        $user = $this->createUser($data);
        match (true) {
            request()->routeIs('admin.create') => $user
                ->update(['is_admin' => UserType::admin()->value]),
            default => null
        };

        $token = $user->createToken(sprintf('%s token', $user->email));
        $user['token'] = $token->getPlainTextToken();

        return $user;
    }
}
