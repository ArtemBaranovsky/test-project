<?php

namespace App\Repositories;

use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;

class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{
    public function save(Subscription $subscription): void
    {
        $subscription->save();
    }
}
