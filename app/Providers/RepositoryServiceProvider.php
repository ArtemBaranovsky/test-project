<?php

namespace App\Providers;


use App\Models\Publication;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\PublicationRepositoryInterface;
use App\Repositories\Contracts\SubscriptionPlanRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\PlanRepository;
use App\Repositories\PublicationRepository;
use App\Repositories\SubscriptionPlanRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use PayPal\Api\Plan;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, function ($app) {
            return new UserRepository($app->make(User::class));
        });

        $this->app->bind(PublicationRepositoryInterface::class, function ($app) {
            return new PublicationRepository($app->make(Publication::class));
        });

        $this->app->bind(SubscriptionRepositoryInterface::class, function ($app) {
            return new SubscriptionRepository($app->make(Subscription::class));
        });

        $this->app->bind(SubscriptionPlanRepositoryInterface::class, function ($app) {
            return new SubscriptionPlanRepository($app->make(Subscription::class));
        });

        $this->app->bind(PlanRepositoryInterface::class, function ($app) {
            return new PlanRepository($app->make(Plan::class));
        });
    }

    /**
     * Bootstrap any application repositories.
     */
    public function boot(): void
    {
    }
}
