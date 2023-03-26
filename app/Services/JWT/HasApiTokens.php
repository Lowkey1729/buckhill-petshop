<?php

namespace App\Services\JWT;

use App\Models\JwtToken;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasApiTokens
{
    public string $plainTextToken;

    public array|object $accessToken;

    /**
     * @return HasMany<JwtToken>
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(JwtToken::class);
    }

    /**
     * @throws Exception
     */
    public function createToken(string $tokenTitle): self
    {
        $jwtService = WebTokenService::initiate($this);
        $this->plainTextToken = $jwtService->issueToken();
        $token = $this->tokens()->create([
            'unique_id' => hash('sha256', $this->plainTextToken),
            'token_title' => $tokenTitle,
            'expires_at' => $jwtService->expiredAt,
        ]);

        $this->accessToken = $token;

        return $this;
    }

    public function currentAccessToken(): string
    {
        return $this->plainTextToken;
    }

    public function withAccessToken(object|array $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
