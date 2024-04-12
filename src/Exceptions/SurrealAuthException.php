<?php

namespace Surreal\Exceptions;

use Exception;

class SurrealAuthException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct("SurrealAuthException: " . $message, 401);
    }
}