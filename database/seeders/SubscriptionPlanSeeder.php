<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'name' => 'starter',
                'price' => 10,
                'publications_available' => 100,
            ],
            [
                'name' => 'standard',
                'price' => 20,
                'publications_available' => 300,
            ],
            [
                'name' => 'business',
                'price' => 30,
                'publications_available' => 500,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
