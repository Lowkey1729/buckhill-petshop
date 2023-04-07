<?php

namespace Tests\Feature\User;

use App\Models\OrderStatus;
use App\Models\Payment;
use Tests\TestCase;
use Throwable;

class UserTest extends TestCase
{
    /**
     * @test
     * @throws Throwable
     */
    public function it_can_view_user_orders(): void
    {
        $this->authenticateUser();
        Payment::factory()->create();
        OrderStatus::factory()->create();
        $this->user?->orders()->create([
            'shipped_at' => now(),
            'payment_id' => Payment::query()->first('id')?->id,

            'products' => [
                "uuid" => fake()->uuid,
                "quantity" => "4"
            ],
            'address' => [
                'billing' => fake()->streetAddress,
                'shipping' => fake()->address,
            ],
            'delivery_fee' => fake()->numberBetween(1, 9),
            'amount' => fake()->numberBetween(100, 890),

            'order_status_id' => OrderStatus::query()
                ->first('id')?->id,
        ]);
        $response = $this->get(route('user.orders'))
            ->assertStatus(200);

        $this->assertArrayHasKey("orders", $response['data']);
    }
}
