<?php

namespace Tests\Unit\JWT;

use App\Models\User;
use App\Services\JWT\WebTokenService;
use Exception;
use Tests\TestCase;

class JWTServiceTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function it_can_generate_valid_token(): void
    {
        $user = User::query()->first();
        $jwtService = new WebTokenService($user);
        $token = $jwtService->issueToken();
        $this->tokenIsAValidOne($token, $user?->uuid);
    }

    /**
     * @test
     * @throws Exception
     */
    public function it_can_store_generated_token(): void
    {
        $user = User::query()->first();
        $token = $user?->createToken("Test token")->getAccessToken();
        $latestToken = $user?->tokens()->latest()->first();
        $this->assertEquals($token?->user_id, $latestToken?->user_id);
        $this->assertEquals($token?->unique_id, $latestToken?->unique_id);
    }

    protected function tokenIsAValidOne(string $token, string $userUUid): void
    {
        $parts = explode('.', $token);
        $this->assertCount(3, $parts);

        $payload = base64_decode($parts[1]);
        $this->assertJson($payload);

        $payload = json_decode($payload, true);
        $this->assertArrayHasKey('iss', $payload);
        $this->assertArrayHasKey('jti', $payload);
        $this->assertArrayHasKey('aud', $payload);
        $this->assertArrayHasKey('iat', $payload);
        $this->assertArrayHasKey('nbf', $payload);
        $this->assertArrayHasKey('exp', $payload);

        $this->assertSame($userUUid, $payload['jti']);
    }
}
