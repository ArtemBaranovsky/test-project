<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    public function initiatePayment($amount);

    public function checkPaymentStatus($transactionId);

    public function confirmPayment($transactionId);

    public function cancelPayment($transactionId);
}
