<?php

namespace Tests\Unit;

use App\Http\Controllers\PaymentController;
use App\Repositories\SubscriptionRepository;
use App\Services\FakePaymentService;
use App\Services\Payment\PayPalExpressGateway;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Tests\TestCase;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Publication;
use App\Services\SubscriptionService;
use App\Services\PaymentService;
use Tests\Factories\RoleFactory;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected                  $user;
    protected Collection|Model $subscription;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind('role', function ($app) {
            return $app->make(RoleFactory::class)->definition();
        });

        $this->user = User::whereHas('roles', function ($query) {
            $query->where('name', 'publisher');
        })->first()->load('roles');

        $this->subscription = $this->user->subscription;
        $paymentGateway         = new PayPalExpressGateway();
        $subscriptionRepository = new SubscriptionRepository($this->subscription);

        $this->subscriptionService = new SubscriptionService($subscriptionRepository);

        $this->paymentService = new PaymentService($paymentGateway, $this->subscriptionService);
    }

    public function testCreatePublicationWithoutActiveSubscription()
    {
        $this->user->subscription->delete();
        $this->user->refresh(); // Обновляем модель пользователя из базы данных
        $this->actingAs($this->user, 'sanctum')->withSession(['sanctum:subscription' => null]);

        $response = $this->withHeaders([
            'Content-Type' => 'application/json',
            'X-CSRF-TOKEN' => csrf_token(),
        ])->postJson('/api/publications', [
            'title'   => "Test Publication of {$this->user->name}",
            'content' => 'Lorem ipsum dolor sit amet',
            'user_id' => $this->user->id,
            'status'  => Publication::STATUS_ACTIVE
        ]);

        $response->assertStatus(403);
        $responseData = $response->decodeResponseJson();
        $message      = $responseData['message'];
        $this->assertTrue(
            strpos($message, 'This action is unauthorized.') !== false ||
            strpos($message, 'You have reached the publication limit.') !== false
        );;
    }

    public function testCreatePublicationWithExceededPublicationLimit()
    {
        $subscription = $this->user->subscription;
        $subscription->status = Subscription::STATUS_ACTIVE;
        $subscription->save();

        $subscription->subscriptionPlan->publications_available = 0;
        $subscription->subscriptionPlan->save();

        $this->actingAs($this->user, 'sanctum');

        $this->subscriptionService->createSubscription([
            'user_id'    => $this->user->id,
            'name'       => 'Exceeded Publication Limit',
            'status'     => Subscription::STATUS_ACTIVE,
            'expires_at' => now()->addMonth(),
            'plan_id'    => $this->user->subscription->subscriptionPlan->id,
        ]);

        $response = $this->postJson('/api/publications', [
            'user_id' => $this->user->id,
            'title'   => 'Test Publication',
            'content' => 'Lorem ipsum dolor sit amet',
        ]);

        $response->assertStatus(403)
            ->assertJson(['message' => 'This action is unauthorized.']);
    }

    public function testCreatePublicationWithActiveSubscription()
    {
        $subscription = $this->user->subscription;
        $subscription->status = Subscription::STATUS_ACTIVE;
        $subscription->save();

        $subscription->subscriptionPlan->publications_available = 10;
        $subscription->subscriptionPlan->save();

        $this->actingAs($this->user, 'sanctum');

        $response = $this->postJson('/api/publications', [
            'user_id'    => $this->user->id,
            'title'   => 'Test Publication',
            'content' => 'Lorem ipsum dolor sit amet',
            'status'     => Subscription::STATUS_ACTIVE,
            'expires_at' => now()->addMonth(),
            'plan_id'    => $this->user->subscription->subscriptionPlan->id,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'title'   => 'Test Publication',
                'content' => 'Lorem ipsum dolor sit amet',
                'user_id' => $this->user->id,
            ]);
    }

    public function testPaymentCallback()
    {
        $transactionId  = 'example_transaction_id';
        $subscriptionId = 'example_subscription_id';

        $request = Request::create(
            '/api/payment/callback',
            'POST',
            [
                'transaction_id'  => $transactionId,
                'subscription_id' => $subscriptionId,
                'price'           => 10.99,
            ]
        );

        $controller = new PaymentController(new FakePaymentService());

        $response = $controller->handlePaymentCallback($request);

        $responseStatus = $response->status();
        $this->assertEquals(200, $responseStatus);
    }
}
