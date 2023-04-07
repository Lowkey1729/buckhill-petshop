<?php

namespace Tests\Unit\JWT;

use App\Models\User;
use App\Services\Enums\UserType;
use Illuminate\Contracts\Auth\Authenticatable;

class GuardTest extends \Tests\TestCase
{
    /** @test */
    public function when_not_authenticated_it_throws_exception(): void
    {
        $this->get(route('admin.user-listing'))
            ->assertStatus(401)
        ;
    }

    /** @test */
    public function when_authenticated_it_can_view_user_listings(): void
    {
        $this->authenticate('jwt');

        $this->get(route('admin.user-listing'))
            ->assertSuccessful();
    }

    protected function authenticate(string $guard = null): void
    {
        /** @var Authenticatable $user*/
        $user = User::query()
            ->where('is_admin', UserType::admin()->value)
            ->first();
        $this->actingAs($user, $guard);
    }
}
