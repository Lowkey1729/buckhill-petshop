<?php

namespace App\Http\Controllers\APIs\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Actions\Auth\Register as RegisterAction;
use App\Services\Helpers\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * This handles the registration of new  users|admins
     * @param RegisterRequest $request
     * @param RegisterAction $action
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(
        RegisterRequest $request,
        RegisterAction $action
    ): JsonResponse {
        $user = $action->registerUser($request);
        return ApiResponse::success($user);
    }

    //
}
