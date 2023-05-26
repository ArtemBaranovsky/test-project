<?php

namespace App\Services;

use App\Contracts\PaymentServiceInterface;

class FakePaymentService implements PaymentServiceInterface
{
    public function initiatePayment($subscriptionId): string
    {
        return 'https://example.com/payment-url';
    }

    public function checkPaymentStatus($transactionId): string
    {
        return 'completed';
    }

    public function processPaymentStatus($paymentStatus, $subscriptionId): void
    {
    }

    public function processPayment($amount)
    {
        // TODO: Implement processPayment() method.
    }
}
