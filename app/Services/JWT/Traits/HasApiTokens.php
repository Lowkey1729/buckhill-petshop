<?php

namespace App\Services\JWT\Traits;

use App\Models\JwtToken;
use App\Services\JWT\WebTokenService;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasApiTokens
{
    protected string $plainTextToken;

    protected JwtToken $accessToken;

    /**
     * @return HasMany<JwtToken>
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(JwtToken::class);
    }

    /**
     * @param string $tokenTitle
     * @return self
     * @throws Exception
     */
    public function createToken(string $tokenTitle): self
    {
        $jwtService = new WebTokenService($this);
        $this->plainTextToken = $jwtService->issueToken();
        $token = $this->tokens()->create([
            'unique_id' => hash('sha256', $this->getPlainTextToken()),
            'token_title' => $tokenTitle,
            'expires_at' => $jwtService->getExpiresAt(),
        ]);
        $this->accessToken = $token;

        return $this;
    }

    /**
     * @return JwtToken
     */
    public function currentAccessToken(): JwtToken
    {
        return $this->getAccessToken();
    }

    /**
     * @param JwtToken $accessToken
     * @return $this
     */
    public function withAccessToken(JwtToken $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainTextToken(): string
    {
        return $this->plainTextToken;
    }

    /**
     * @return JwtToken
     */
    public function getAccessToken(): JwtToken
    {
        return $this->accessToken;
    }

    /**
     * @return void
     */
    public function deleteAccessToken(): void
    {
        $token = request()->bearerToken();
        if ($token) {
            JwtToken::query()
                ->where(
                    'unique_id',
                    hash('sha256', $token)
                )->delete();
        }
    }
}
