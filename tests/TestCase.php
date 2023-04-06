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

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    /**
     * @throws Throwable
     */
    public function getAdminAccessToken(): string
    {
        $this->user = User::query()->first();
        $this->user?->update(['is_admin' => UserType::admin()->value]);
        $response = $this->json('POST', route('admin.login'), [
            'email' => $this->user?->email,
            'password' => "admin",
        ])->decodeResponseJson();

        return $response['data']['token'];
    }

    /**
     * @throws Throwable
     */
    public function getUserAccessToken(): string
    {
        $this->user = User::query()->first();
        $response = $this->json('POST', route('user.login'), [
            'email' => $this->user?->email,
            'password' => "userpassword",
        ]);

        return $response['data']['token'];
    }
}
