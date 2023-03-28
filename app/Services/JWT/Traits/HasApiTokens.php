<?php

namespace App\Services\JWT\Traits;

use App\Models\JwtToken;
use App\Services\JWT\WebTokenService;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasApiTokens
{
    protected string $plainTextToken;

    protected array|object $accessToken;

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

    public function currentAccessToken(): array|object
    {
        return $this->getAccessToken();
    }

    public function withAccessToken(object|array $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getPlainTextToken(): string
    {
        return $this->plainTextToken;
    }

    public function getAccessToken(): object|array
    {
        return $this->accessToken;
    }
}
