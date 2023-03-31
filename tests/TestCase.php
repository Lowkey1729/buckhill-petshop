<?php

namespace Tests;

use App\Models\User;
use App\Services\Enums\UserType;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Throwable;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;

    public User|null $user;

    /**
     * @throws Throwable
     */
    public function getAdminAccessToken(): string
    {
        $user = User::factory()->create();
        $user->update(['is_admin' => UserType::admin()->value]);
        $response = $this->json('POST', route('admin.login'), [
            'email' => $user->email,
            'password' => "password",
        ])->decodeResponseJson();

        return $response['data']['token'];
    }

    /**
     * @throws Throwable
     */
    public function getUserAccessToken(): string
    {
        $this->user = User::factory()->create();
        $response = $this->json('POST', route('user.login'), [
            'email' => $this->user->email,
            'password' => "password",
        ]);

        return $response['data']['token'];
    }

    /**
     * Reset the migrations
     */
    public function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }
}
