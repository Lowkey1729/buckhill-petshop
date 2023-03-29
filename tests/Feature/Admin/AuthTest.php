<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        User::factory(1)->create();
    }

    /**
     * @test
     */
    public function it_validates_login_request(): void
    {
        $user = User::query()->first();
        $response = $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_can_check_if_credentials_are_correct(): void
    {
        $response = $this->json('POST', route('admin.login'), [
            'email' => 'olarewajumojeed9@gmail',
            'password' => 'olarewaju9',
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_can_check_if_user_email_is_verified(): void
    {
        $user = User::query()->first();
        $user?->update(['email_verified_at' => null]);
        $response = $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
            'password' => 1234,
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_can_login_admin(): void
    {
        $user = User::query()->first();
        $user?->update(['is_admin' => UserType::admin()->value]);
        $response = $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
            'password' => 1234,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('token', $data['data']);
    }

    /**
     * @test
     */
    public function it_can_generate_token_for_only_admin_users(): void
    {
        $user = User::query()->first();
        $user?->update(['is_admin' => 0]);
        $response = $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
            'password' => 1234,
        ]);

        $response->assertStatus(403);
    }
}
