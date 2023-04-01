<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Services\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Throwable;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_validates_login_request(): void
    {
        $user = User::query()->first();
        $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
        ])->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_can_check_if_credentials_are_correct(): void
    {
        $this->json('POST', route('admin.login'), [
            'email' => 'olarewajumojeed9@gmail',
            'password' => 'olarewaju9',
        ])->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_can_check_if_user_email_is_verified(): void
    {
        $user = User::query()->first();
        $user?->update(['email_verified_at' => null]);
        $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
            'password' => "password",
        ])->assertStatus(401);
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
            'password' => "password",
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
        $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
            'password' => "password",
        ])->assertStatus(403);
    }


    /**
     * @test
     */
    public function it_can_validate_create_request(): void
    {
        $this->json('POST', route('admin.create'), [])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_can_generate_token_for_created_user(): void
    {
        $response = $this->json('POST', route('admin.create'), [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'phone_number' => fake()->phoneNumber,
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_admin' => UserType::admin()->value,
            'address' => fake()->address,
            'marketing' => '1',
            'avatar' => fake()->uuid,
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertArrayHasKey('token', $data['data']);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_can_logout_user(): void
    {
        $token = $this->getAdminAccessToken();


        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('admin.logout'))
            ->assertStatus(200);
    }
}
