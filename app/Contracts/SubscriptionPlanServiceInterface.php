<?php

namespace App\Contracts;

interface SubscriptionPlanServiceInterface
{
    public function getAllSubscriptionPlans(): \Illuminate\Database\Eloquent\Collection;

    public function getSubscriptionPlanById($id);

    public function createSubscriptionPlan($data);

    public function updateSubscriptionPlan($id, $data);

    public function deleteSubscriptionPlan($id): void;
}
