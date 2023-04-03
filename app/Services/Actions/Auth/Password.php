<?php

namespace App\Services\Actions\Auth;

use App\Exceptions\PasswordError;
use App\Http\Requests\Auth\PasswordRequest;
use App\Models\User;
use App\Services\Concerns\Auth\Password as PasswordTrait;

class Password
{
    use PasswordTrait;

    /**
     * This handles the resetting of password.
     * A token is generated if the email of the user exists in the
     * system
     * @param PasswordRequest $request
     * @return string
     * @throws PasswordError
     */
    public function forgotUserPassword(PasswordRequest $request): string
    {
        $user = User::query()->where('email', $request->only('email'))->first();
        if (!$user) {
            throw new PasswordError('Invalid user email', 404);
        }
        $token = \Illuminate\Support\Facades\Password::broker()->createToken($user);
        if (!$token) {
            throw new PasswordError('Unable to generate token');
        }
        return $token;
    }

    /**
     * The token gotten from the "forgotPassword" method
     * is what is used to update the password of the user.
     * @param PasswordRequest $request
     * @return array
     */
    public function resetUserPasswordToken(PasswordRequest $request): array
    {
        $data = $request->only('email', 'password', 'password_confirmation', 'token');
        $response = \Illuminate\Support\Facades\Password::broker()->reset($data, function (User $user, $password) {
            $user->forceFill([
                'password' => bcrypt($password),
            ]);
            $user->save();
        });

        return $this->passwordResetStatus($response);
    }
}
