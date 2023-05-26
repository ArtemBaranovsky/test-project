<?php

namespace App\Rules;

use App\Models\SubscriptionPlan;
use Illuminate\Contracts\Validation\Rule;

class PriceMatchesSubscriptionPlan implements Rule
{

    public function __construct(protected SubscriptionPlan $subscriptionPlan)
    {
    }

    public function passes($attribute, $value): bool
    {
        return $value == $this->subscriptionPlan->price;
    }

    public function message(): string
    {
        return 'The price does not match the selected subscription plan.';
    }
}

