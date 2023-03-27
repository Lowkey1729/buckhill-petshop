<?php

namespace App\Services\JWT;

use App\Models\JwtToken;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\TransientToken;

final class Guard
{
    /**
     * The authentication factory implementation.
     */
    protected AuthFactory $auth;

    /**
     * The number of minutes tokens should be allowed to remain valid.
     */
    protected int|null $expiration;

    /**
     * The provider name.
     */
    protected string|null $provider;

    /**
     * Create a new guard instance.
     *
     * @return void
     */
    public function __construct(AuthFactory $auth, int $expiration = null, string $provider = null)
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
        foreach (Arr::wrap(config('jwt.guard', 'web')) as $guard) {
            if ($user = $this->auth->guard($guard)->user()) {
                return $this->supportsTokens($user)
                    ? $user->withAccessToken(new TransientToken)
                    : $user;
            }
        }

        if ($token = $request->bearerToken()) {
            $accessToken = JwtToken::query()
                ->with('user')
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
     * Determine if the tokenable model supports API tokens.
     *
     * @param  mixed  $tokenable
     */
    protected function supportsTokens(mixed $tokenable = null): bool
    {
        return $tokenable && in_array(HasApiTokens::class, class_uses_recursive(
            $tokenable::class
        ));
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
