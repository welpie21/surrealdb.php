<?php

namespace Surreal\classes;

class SurrealAuthResponse
{
    public int $code;
    public string $details;
    public string $token;

    public function __construct(array $input)
    {
        $this->code = $input["code"];
        $this->details = $input["details"];
        $this->token = $input["token"];
    }
}