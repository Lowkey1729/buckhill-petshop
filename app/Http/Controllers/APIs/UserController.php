<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Helpers\ApiResponse;
use App\Services\Traits\Auth\Login as LoginTrait;
use App\Services\Traits\Auth\Password as PasswordTrait;
use App\Services\Traits\Auth\Register as RegisterTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    use LoginTrait;
    use RegisterTrait;
    use PasswordTrait;

    /**
     * This handles the display of the authenticated
     * user's details.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function viewUser(UserRequest $request): JsonResponse
    {
        $user = (object) $request->user();
        return ApiResponse::success($user);
    }

    /**
     * This handles the authentication of the user before
     * gaining access to the system
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function login(UserRequest $request): JsonResponse
    {
        $data = $request->all();
        $this->failedAuthentication($data);

        try {
            $token = $this->user->createToken(sprintf('%s token', $this->user->email));
        } catch (\Exception $exception) {
            Log::error($exception);

            return ApiResponse::failed('An unexpected error was encountered.', httpStatusCode: 500);
        }

        return ApiResponse::success(['token' => $token->getPlainTextToken()]);
    }

    /**
     * This handles the storing of users into the system.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function register(UserRequest $request): JsonResponse
    {
        $data = $request->all();
        $user = $this->createUser($data);
        try {
            $token = $user->createToken(sprintf('%s token', $user->email));
        } catch (\Exception $exception) {
            Log::error($exception);

            return ApiResponse::failed('An unexpected error was encountered.', httpStatusCode: 500);
        }
        $user['token'] = $token->getPlainTextToken();

        return ApiResponse::success($user);
    }

    /**
     * This displays all the current orders that belongs to
     * the authenticated user.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function orders(UserRequest $request): JsonResponse
    {
        $user = $request->user()?->load(['orders']);
        $orders = $user?->orders()->with(['payment', 'orderStatus']);
        if ($orders?->count() === 0) {
            return ApiResponse::failed('You have no orders', httpStatusCode: 404);
        }
        $orders = $orders?->paginate(10);
        return ApiResponse::success(['orders' => $orders]);
    }

    /**
     * This handles the resetting of password.
     * A token is generated if the email of the user exists in the
     * system
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function forgotPassword(UserRequest $request): JsonResponse
    {
        try {
            $user = User::query()->where('email', $request->only('email'))->first();
            if (!$user) {
                return ApiResponse::failed('Invalid user email', httpStatusCode: 404);
            }
            $token = Password::broker()->createToken($user);
            if (!$token) {
                return ApiResponse::failed('Unable to generate token');
            }
            return ApiResponse::success(['reset_token' => $token]);
        } catch (\Exception $exception) {
            Log::error($exception);

            return ApiResponse::failed('An unexpected error was encountered.', httpStatusCode: 500);
        }
    }

    /**
     * The token gotten from the "forgotPassword" method
     * is what is used to update the password of the user.
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function resetPasswordToken(UserRequest $request): JsonResponse
    {
        $data = $request->only('email', 'password', 'password_confirmation', 'token');
        try {
            $response = Password::broker()->reset($data, function (User $user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ]);
                $user->save();
            });
        } catch (\Exception $exception) {
            Log::error($exception);

            return ApiResponse::failed('An unexpected error was encountered.', httpStatusCode: 500);
        }

        $passwordReset = $this->passwordResetStatus($response);
        if (!$passwordReset['status']) {
            return ApiResponse::failed($passwordReset['message']);
        }

        return ApiResponse::success();
    }

    /**
     * This handles the updating of existing users' details
     * @param UserRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function editUser(UserRequest $request, string $uuid): JsonResponse
    {
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            return ApiResponse::failed('User not found', httpStatusCode: 404);
        }
        $user->update(
            array_filter(
                $request->all(),
                function ($x) {
                    return !is_null($x);
                }
            )
        );
        return ApiResponse::success($user);
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function logout(UserRequest $request): JsonResponse
    {
        $request->user()?->deleteAccessToken();
        return ApiResponse::success();
    }

    /**
     * This handles the soft removal of an existing user
     * from the system,
     * @param UserRequest $request
     * @param string $uuid
     * @return JsonResponse
     */
    public function deleteUser(UserRequest $request, string $uuid): JsonResponse
    {
        $user = User::whereUuid($uuid)->first();
        if (!$user) {
            return ApiResponse::failed('User not found', httpStatusCode: 404);
        }
        $user->delete();
        request()->user()?->deleteAccessToken();
        return ApiResponse::success();
    }
}
