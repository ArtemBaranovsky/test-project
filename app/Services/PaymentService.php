<?php

namespace App\Services;

use App\Contracts\PaymentServiceInterface;
use App\Services\Payment\PaymentGateway;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(protected PaymentGateway      $paymentGateway,
                                protected SubscriptionService $subscriptionService)
    {
    }

    public function initiatePayment($subscriptionId)
    {
        $paymentUrl = $this->paymentGateway->initiatePayment($subscriptionId);

        return $paymentUrl;
    }

    public function checkPaymentStatus($transactionId)
    {
        $paymentStatus = $this->paymentGateway->checkPaymentStatus($transactionId);

        return $paymentStatus;
    }

    public function processPaymentStatus($paymentStatus, $subscriptionId): void
    {
        $subscription = $this->subscriptionService->getSubscriptionById($subscriptionId);

        if ($paymentStatus === 'completed') {
            $subscription->status = 'active';
            $subscription->save();

        } elseif ($paymentStatus === 'pending') {
            $subscription->status = 'pending';
            $subscription->save();

        } elseif ($paymentStatus === 'cancelled') {
            $subscription->status = 'cancelled';
            $subscription->save();
        }
    }

    public function processPayment($amount)
    {
        // TODO: Implement processPayment() method.
    }
}

