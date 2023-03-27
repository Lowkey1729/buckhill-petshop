<?php

namespace App\Services\JWT\Traits;

use App\Models\JwtToken;
use App\Services\JWT\WebTokenService;
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
        $this->setPlainTextToken($jwtService->issueToken());
        $token = $this->tokens()->create([
            'unique_id' => hash('sha256', $this->getPlainTextToken()),
            'token_title' => $tokenTitle,
            'expires_at' => $jwtService->expiredAt,
        ]);
        $this->setAccessToken($token);

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

    public function setPlainTextToken(string $plainTextToken): void
    {
        $this->plainTextToken = $plainTextToken;
    }

    public function getAccessToken(): object|array
    {
        return $this->accessToken;
    }

    public function setAccessToken(object|array $accessToken): void
    {
        $this->accessToken = $accessToken;
    }
}
