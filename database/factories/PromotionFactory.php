<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => fake()->text,
            'metadata' => $this->getMetaData(),
        ];
    }

    protected function getMetaData(): array
    {
        $date = fake()->date;

        return [
            'valid_from' => $date,
            'valid_to' => date(
                'Y-m-d',
                (int) strtotime($date.' + 10 days')
            ),
        ];
    }
}
