<?php

namespace App\Services\Payment;

abstract class PaymentGateway implements PaymentGatewayInterface
{

    public function __construct(protected string $apiKey,
                                protected string $apiSecret)
    {
    }
}
