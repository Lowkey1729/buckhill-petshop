<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Services\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentType = $this->getPaymentType();

        return [
            'type' => $paymentType,
            'details' => $this->getPaymentDetails($paymentType),
        ];
    }

    protected function getPaymentType(): string|int
    {
        $paymentTypes = [
            PaymentType::credit_card(),
            PaymentType::cash_on_delivery(),
            PaymentType::bank_transfer(),
        ];

        return $paymentTypes[rand(0, 2)];
    }

    protected function getPaymentDetails(string|int $paymentType): array
    {
        return match ($paymentType) {
            PaymentType::cash_on_delivery()->label => [
                'holder_name' => fake()->name,
                'number' => fake()->creditCardNumber,
                'ccv' => fake()->randomNumber(3),
                'expire_date' => fake()->creditCardExpirationDate,
            ],

            PaymentType::bank_transfer()->label => [
                'first_name' => fake()->firstName,
                'last_name' => fake()->lastName,
                'address' => fake()->address,
            ],

            PaymentType::credit_card()->label => [
                'swift' => fake()->swiftBicNumber,
                'iban' => fake()->iban,
                'name' => fake()->name,
            ],

            default => []
        };
    }
}
