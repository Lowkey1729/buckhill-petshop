<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName,
            'is_admin' => $this->getPrivilege(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('userpassword'), // password
            'avatar' => fake()->uuid,
            'address' => fake()->address,
            'phone_number' => fake()->phoneNumber,
            'is_marketing' => $this->getPrivilege(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * value 0 means the user is not an admin
     * value 1 means the user is an admin
     */
    protected function getPrivilege(): int
    {
        $roles = [0, 1];

        return $roles[rand(0, 1)];
    }
}
