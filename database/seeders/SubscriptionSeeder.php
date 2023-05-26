<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriptionPlanIDs = SubscriptionPlan::pluck('id')->all();

        foreach (User::all() as $user) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => current($subscriptionPlanIDs),
                'name' => "Test subscription for $user->name",
                'status' => Subscription::STATUS_ACTIVE,
                'expires_at' => now()->addMonth()
            ]);
            $subscription->save();

            next($subscriptionPlanIDs);
        }
    }
}
