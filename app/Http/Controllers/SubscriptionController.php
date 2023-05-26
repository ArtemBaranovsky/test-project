<?php

namespace App\Http\Controllers;

use App\Contracts\SubscriptionServiceInterface;
use App\Http\Requests\SubscriptionRequest;

class SubscriptionController extends Controller
{

    public function __construct(protected SubscriptionServiceInterface $subscriptionService)
    {
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $subscriptions = $this->subscriptionService->getAllSubscriptions();

        return response()->json($subscriptions, 200);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $subscription = $this->subscriptionService->getSubscriptionById($id);

        if (!$subscription) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        return response()->json($subscription, 200);
    }

    public function store(SubscriptionRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();

        $subscription = $this->subscriptionService->createSubscription($data);

        return response()->json($subscription, 201);
    }

    public function update(SubscriptionRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();

        $subscription = $this->subscriptionService->updateSubscription($id, $data);

        if (!$subscription) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        return response()->json($subscription, 200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $result = $this->subscriptionService->deleteSubscription($id);

        if (!$result) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        return response()->json(['message' => 'Subscription deleted successfully'], 200);
    }
}
