<?php

namespace Database\Factories;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentRequest>
 */
class PaymentRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $letters = ['A', 'b', 'C', 'd', 'E', 'f', 'G', 'h', 'I', 'j', 'K', 'l', 'M', 'n', 'O', 'p', 'Q', 'r', 'S', 't', 'U', 'v', 'W', 'x', 'Y', 'z'];
        $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        $trx = fake()->randomElement($letters) .
            fake()->randomElement($numbers) .
            fake()->randomElement($letters) .
            fake()->randomElement($letters) .
            fake()->randomElement($numbers) .
            fake()->randomElement($letters) .
            fake()->randomElement($numbers) .
            fake()->randomElement($numbers);
        return [
            'trxid' => strtoupper($trx),
            'amount' => fake()->randomNumber(3,true),
            'payment_method' => fake()->randomElement([
                'bkash','nagad','rocket','upay','bank payment'
            ]),
            'merchant_id' => Merchant::all()->random(),
            'reference' => $trx.rand(1,10),
            'currency' => $this->faker->randomElement(['BDT', 'USD', 'EURO']),
            'callback_url' => fake()->url(),
            'cust_name' => fake()->name,
            'cust_phone' => fake()->phoneNumber,
            'cust_address' => fake()->address,
            'checkout_items' => fake()->text(30),
            'note' => fake()->text(30),
            'ext_field_1' => fake()->text(30),
            'ext_field_2' => fake()->text(30),
            'issue_time' => now()->addDay(rand(1, 10))->addMinute(10)->format('Y-m-d H:i:s'),
            'agent' => fake()->randomElement([1, 2, 3]),
            'dso' => fake()->randomElement([1, 2, 3]),
            'partner' => fake()->randomElement([1, 2, 3]),
            'modem_id' => fake()->randomElement([1, 2, 3]),
            'device_id' => fake()->randomElement([1, 2, 3]),
            'ip' => fake()->ipv4,
            'user_agent' => fake()->userAgent,
            'status' => fake()->randomElement([0,1, 2, 3]),
        ];
    }
}
