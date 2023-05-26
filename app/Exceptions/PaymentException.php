<?php

namespace App\Exceptions;

use Exception;

class PaymentException  extends Exception
{
    protected $message = 'Payment processing exception.';
}
