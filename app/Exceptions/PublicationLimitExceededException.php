<?php

namespace App\Exceptions;

use Exception;

class PublicationLimitExceededException extends Exception
{
    protected $message = 'User has reached the number of available publications.';
}
