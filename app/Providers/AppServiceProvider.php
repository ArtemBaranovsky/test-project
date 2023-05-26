<?php

namespace App\Providers;

use App\Contracts\PaymentServiceInterface;
use App\Contracts\PublicationServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Contracts\UserServiceInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\PayPalExpressGateway;
use App\Services\PaymentService;
use App\Services\PublicationService;
use App\Services\SubscriptionService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, PayPalExpressGateway::class);
//        $this->app->bind(PaymentGatewayInterface::class, StripePaymentGateway::class);

        $this->app->bind(SubscriptionServiceInterface::class, function ($app) {
            return new SubscriptionService(
                $app->make(SubscriptionRepositoryInterface::class)
            );
        });

        $this->app->bind(PaymentServiceInterface::class, function ($app) {
            return new PaymentService(
                $app->make(PaymentGatewayInterface::class),
                $app->make(SubscriptionServiceInterface::class)
            );
        });

        $this->app->bind(PublicationServiceInterface::class, PublicationService::class);


        $this->app->bind(UserServiceInterface::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
