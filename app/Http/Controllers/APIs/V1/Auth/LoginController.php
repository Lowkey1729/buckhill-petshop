<?php

namespace App\Http\Controllers\APIs\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Actions\Auth\Login as LoginAction;
use App\Services\Helpers\ApiResponse;
use App\Services\Traits\Auth\Login as LoginTrait;
use Exception;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    use LoginTrait;

    public function __construct(protected readonly LoginAction $action)
    {
    }

    /**
     * This method handles the authentication of the admin/user
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $token = $this->action->loginUser($request);
        return ApiResponse::success(['token' => $token?->getPlainTextToken()]);
    }
}
