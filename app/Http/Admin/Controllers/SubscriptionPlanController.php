<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\SubscriptionPlanRequest;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionPlanService;

class SubscriptionPlanController extends Controller
{
    public function __construct(protected SubscriptionPlanService $subscriptionPlanService)
    {
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $subscriptionPlans = $this->subscriptionPlanService->getAllSubscriptionPlans();

        return view('admin.subscription-plans.index', compact('subscriptionPlans'));
    }

    public function create(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('admin.subscription-plans.create');
    }

    public function store(SubscriptionPlanRequest $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $this->subscriptionPlanService->createSubscriptionPlan($data);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $subscriptionPlan = $this->subscriptionPlanService->getSubscriptionPlanById($id);

        return view('admin.subscription-plans.edit', compact('subscriptionPlan'));
    }

    public function update(SubscriptionPlanRequest $request, $id): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();

        $this->subscriptionPlanService->updateSubscriptionPlan($id, $data);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $this->subscriptionPlanService->deleteSubscriptionPlan($id);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }
}
