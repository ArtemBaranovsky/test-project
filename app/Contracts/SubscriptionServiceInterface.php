<?php

namespace App\Contracts;

use App\Models\Subscription;

interface SubscriptionServiceInterface
{
    public function activateSubscription(Subscription $subscriptionId);

    public function getAllSubscriptions(): \Illuminate\Database\Eloquent\Collection;

    public function getSubscriptionById(Subscription $id);

    public function createOrUpdateSubscription($data, Subscription $id = null);

    public function createSubscription($data): Subscription;

    public function updateSubscription($id, $data);

    public function deleteSubscription($id): bool;
}
