<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;
use Override;

class AuthResponse extends AbstractResponse
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