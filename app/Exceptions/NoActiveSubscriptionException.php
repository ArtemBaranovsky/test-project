<?php

namespace App\Exceptions;

use Exception;

class NoActiveSubscriptionException extends Exception
{
    protected $message = 'User has no active subscription.';
}
