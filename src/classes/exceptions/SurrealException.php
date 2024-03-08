<?php

namespace Surreal\classes\exceptions;

use Exception;

class SurrealException extends Exception
{
    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}