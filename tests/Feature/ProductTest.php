<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Throwable;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @throws Throwable
     */
    public function it_can_validate_create_product_request(): void
    {
        $token = $this->getUserAccessToken();
        $this->withHeaders(
            [
                'Authorization' => "Bearer " . $token
            ]
        )->json('POST', route('product.create-product'), [

        ])->assertStatus(422);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_can_create_a_product(): void
    {
        $token = $this->getUserAccessToken();
        $this->withHeaders(
            [
                'Authorization' => "Bearer " . $token
            ]
        )->json('POST', route('product.create-product'), [
            'category_uuid' => Category::query()->first()?->uuid,
            'title' => fake()->title,
            'price' => fake()->numberBetween(123.9, 890.34),
            'description' => fake()->sentence,
            'brand' => fake()->word,
            'image' => fake()->uuid

        ])->assertStatus(200);
    }


    /**
     * @test
     * @throws Throwable
     */
    public function it_can_update_a_product(): void
    {
        $token = $this->getUserAccessToken();
        $this->withHeaders(
            [
                'Authorization' => "Bearer " . $token
            ]
        )->json('PUT', route(
            'product.update-product',
            Product::query()->first()?->uuid
        ), [
            'title' => fake()->title,
            'price' => fake()->numberBetween(123.9, 890.34),
            'description' => fake()->sentence,
            'brand' => fake()->word,
            'image' => fake()->uuid

        ])->assertStatus(200);
    }
}
