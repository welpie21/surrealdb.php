<?php

namespace Surreal\classes\responses;

use Surreal\interface\ResponseInterface;

class AuthResponse implements ResponseInterface
{
    const array KEYS = ["code", "details", "token"];

    public readonly int $code;
    public readonly mixed $details;
    public readonly ?string $token;

    public function __construct(array $data)
    {
        $this->code = $data["code"];
        $this->details = $data["details"];
        $this->token = $data["token"];
    }
}