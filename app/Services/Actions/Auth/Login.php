<?php

namespace App\Services\Actions\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Services\Traits\Auth\Login as LoginTrait;
use Exception;

class Login
{
    use LoginTrait;

    /**
     * @param LoginRequest $request
     * @return User|null
     * @throws Exception
     */
    public function loginUser(LoginRequest $request): User|null
    {
        $data = $request->all();
        $this->failedAuthentication($data);
        $user = $this->getUser();
        return $user?->createToken(sprintf('%s token', $this->user?->email));
    }
}
