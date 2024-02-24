<?php

namespace Surreal\classes\exceptions;

class UnknownResponseException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Something went wrong with the response. Please try again.", 500);
    }
}