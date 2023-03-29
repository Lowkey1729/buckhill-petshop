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

    /**
     * Reset the migrations
     */
    public function tearDown(): void
    {
        $this->artisan('migrate:reset');
        parent::tearDown();
    }

    /**
     * @throws Throwable
     */
    public function getAccessToken(): string
    {
        $user = User::query()->first();
        $user?->update(['is_admin' => UserType::admin()->value]);
        $response = $this->json('POST', route('admin.login'), [
            'email' => $user?->email,
            'password' => 1234,
        ])->decodeResponseJson();

        return $response['data']['token'];
    }
}
