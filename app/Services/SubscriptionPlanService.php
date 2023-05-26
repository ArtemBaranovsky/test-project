<?php

namespace App\Services;

use App\Contracts\SubscriptionPlanServiceInterface;
use App\Repositories\Contracts\SubscriptionPlanRepositoryInterface;

class SubscriptionPlanService implements SubscriptionPlanServiceInterface
{
    protected SubscriptionPlanRepositoryInterface $subscriptionPlanRepository;

    public function __construct(SubscriptionPlanRepositoryInterface $subscriptionPlanRepository)
    {
        $this->subscriptionPlanRepository = $subscriptionPlanRepository;
    }

    public function getAllSubscriptionPlans(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->subscriptionPlanRepository->getAll();
    }

    public function getSubscriptionPlanById($id)
    {
        return $this->subscriptionPlanRepository->find($id);
    }

    public function createSubscriptionPlan($data)
    {
        return $this->subscriptionPlanRepository->create($data);
    }

    public function updateSubscriptionPlan($id, $data)
    {
        return $this->subscriptionPlanRepository->update($id, $data);
    }

    public function deleteSubscriptionPlan($id): void
    {
        $this->subscriptionPlanRepository->delete($id);
    }
}
