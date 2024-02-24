<?php

namespace Surreal\classes\auth;

use Surreal\abstracts\AbstractAuth;

class SurrealAuth extends AbstractAuth
{
    public function validate(): bool
    {
        return true;
    }
}