<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingPlan;

class PricingPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'price' => '1%',
                'price_type' => 'Per successful charge',
                'description' => 'Perfect for small businesses',
                'features' => [
                    'All payment methods',
                    '24/7 support',
                    'Basic dashboard',
                    'Transaction history'
                ],
                'button_text' => 'Become a Merchant',
                'button_link' => route('customer.view_create_account'),
                'is_featured' => false,
                'display_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Professional',
                'price' => '1%',
                'price_type' => 'Per successful charge',
                'description' => 'For growing businesses',
                'features' => [
                    'All Starter features',
                    'Advanced analytics',
                    'Custom integration',
                    'Priority support'
                ],
                'button_text' => 'Become a Merchant',
                'button_link' => route('customer.view_create_account'),
                'is_featured' => true,
                'display_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Enterprise',
                'price' => 'Custom',
                'price_type' => 'For large businesses',
                'description' => 'Tailored solutions for enterprise',
                'features' => [
                    'All Pro features',
                    'Dedicated support',
                    'Custom pricing',
                    'Custom features'
                ],
                'button_text' => 'Contact Sales',
                'button_link' => route('company') . '#contact',
                'is_featured' => false,
                'display_order' => 3,
                'status' => true,
            ],
        ];

        foreach ($plans as $plan) {
            PricingPlan::create($plan);
        }
    }
}
