<?php

namespace Surreal\classes\exceptions;

use Exception;

class SurrealException extends Exception
{
    public function __construct(string $message)
    {
        $message = "SurrealException: " . $message;
        parent::__construct($message);
    }
}