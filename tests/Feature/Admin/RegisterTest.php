<?php

namespace Tests\Feature\Admin;

use App\Services\Enums\UserType;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_validate_create_request(): void
    {
        $response = $this->json('POST', route('admin.create'), []);

        $response->assertStatus(422);
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
}
