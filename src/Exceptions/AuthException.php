<?php

namespace Surreal\Exceptions;

use Exception;

class AuthException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("AuthException: " . $message, 401);
    }
}