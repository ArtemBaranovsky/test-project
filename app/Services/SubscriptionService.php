<?php

namespace App\Services;

use App\Contracts\SubscriptionServiceInterface;
use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;

class SubscriptionService implements SubscriptionServiceInterface
{

    public function __construct(private SubscriptionRepositoryInterface $subscriptionRepository)
    {
    }

    /**
     * @param $subscriptionId
     * @return mixed
     */
    public function activateSubscription($subscriptionId)
    {
        $subscription = $this->subscriptionRepository->find($subscriptionId);
        $subscription->activate();

        $this->subscriptionRepository->save($subscription);

        return $subscription;
    }

    public function getAllSubscriptions(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->subscriptionRepository->getAll();
    }

    public function getSubscriptionById(Subscription $id)
    {
        return $this->subscriptionRepository->find($id);
    }

    public function createOrUpdateSubscription($data, Subscription $id = null)
    {
        if ($id) {
            $subscription = $this->subscriptionRepository->find($id);

            if (!$subscription) {
                return null;
            }
        } else {
            $subscription = new Subscription();
        }

        $subscription->name = $data['name'];
        $subscription->user_id = $data['user_id'];
        $subscription->plan_id = $data['plan_id'];
        $subscription->expires_at = $data['expires_at'];
        $subscription->status = $data['status'];

        $this->subscriptionRepository->save($subscription);

        return $subscription;
    }

    public function createSubscription($data): Subscription
    {
        return $this->createOrUpdateSubscription($data);
    }

    public function updateSubscription($id, $data)
    {
        return $this->createOrUpdateSubscription($data, $id);
    }

    public function deleteSubscription($id): bool
    {
        $subscription = $this->subscriptionRepository->find($id);

        if (!$subscription) {
            return false;
        }

        $this->subscriptionRepository->delete($subscription);

        return true;
    }
}

