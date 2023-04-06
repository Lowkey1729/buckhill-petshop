<?php

namespace Tests\Feature\User;

use App\Models\User;
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
        $this->json('POST', route('user.login'), [
            'email' => $user?->email,
        ])->assertStatus(422);
    }


    /**
     * @test
     */
    public function it_can_check_if_credentials_are_correct(): void
    {
        $this->json('POST', route('user.login'), [
            'email' => 'olarewajumojeed9@gmail',
            'password' => 'olarewaju9',
        ])->assertStatus(401);
    }

    /**
     * @test
     */
    public function it_can_generate_token_for_only_users(): void
    {
        $user = User::query()->first();
        $this->json('POST', route('user.login'), [
            'email' => $user?->email,
            'password' => "userpassword",
        ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_can_validate_register_request(): void
    {
        $this->json('POST', route('user.register'), [])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function it_can_generate_token_for_created_user(): void
    {
        $response = $this->json('POST', route('user.register'), [
            'first_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'email' => fake()->email,
            'phone_number' => fake()->phoneNumber,
            'password' => 'password',
            'password_confirmation' => 'password',
            'address' => fake()->address,
            'marketing' => '1',
            'avatar' => fake()->uuid,
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response['data']);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_can_logout_user(): void
    {
        $token = $this->getUserAccessToken();


        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('user.logout'))
            ->assertStatus(200);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_can_create_token_to_reset_a_user_password(): void
    {
        $token = $this->getUserAccessToken();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('user.forgot-password'), [
            'email' => $this->user?->email
        ]);
        $this->assertArrayHasKey('reset_token', $response['data']);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_can_reset_password_via_token_for_a_user(): void
    {
        $token = $this->getUserAccessToken();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('user.forgot-password'), [
            'email' => $this->user?->email
        ]);
        $token = $response['data']['reset_token'];
        $response = $this
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])
            ->json('POST', route('user.reset-password-token'), [
                'email' => $this->user?->email,
                'token' => $token,
                'password' => 'password',
                'password_confirmation' => 'password'
            ]);


        $this->assertTrue($response['error'] === null);
    }
}
