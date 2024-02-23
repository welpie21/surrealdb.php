<?php

namespace Surreal\classes\response;

readonly class SurrealAuthResponse
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