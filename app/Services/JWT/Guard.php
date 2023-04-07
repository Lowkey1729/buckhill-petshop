<?php

namespace App\Services\JWT;

use App\Models\JwtToken;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;

final class Guard
{
    /**
     * The authentication factory implementation.
     */
    protected AuthFactory $auth;

    /**
     * The number of minutes tokens should be allowed to remain valid.
     */
    protected string|null $expiration;

    /**
     * The provider name.
     */
    protected string|null $provider;

    /**
     * Create a new guard instance.
     *
     * @param AuthFactory $auth
     * @param string|null $expiration
     * @param string|null $provider
     */
    public function __construct(AuthFactory $auth, string|null $expiration = null, string|null $provider = null)
    {
        $this->auth = $auth;
        $this->expiration = $expiration;
        $this->provider = $provider;
    }

    /**
     * @return User|Authenticatable|void|null
     *
     * @throws Exception
     */
    public function __invoke(Request $request, ?User $user)
    {
        if ($request->bearerToken()) {
            $token = $request->bearerToken();
            $accessToken = JwtToken::query()
                ->with('user')
                ->whereHas('user')
                ->where('unique_id', hash('sha256', $token))->first();
            if (! $accessToken) {
                return;
            }

            if (! $this->isValidAccessToken($accessToken->user, $token)) {
                return;
            }

            $accessToken->update(['last_used_at' => now()]);

            return $accessToken->user;
        }
    }

    /**
     * Determine if the provided access token is valid.
     *
     * @throws Exception
     */
    protected function isValidAccessToken(?User $user, mixed $token): bool
    {
        if (! $token) {
            return false;
        }

        return (new WebTokenService($user))->validateToken($token);
    }
}
