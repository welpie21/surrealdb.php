<?php

namespace Surreal\classes\responses;

readonly class AuthenticationResponse
{
    public function __construct(
        public int    $code,
        public string $token,
        public string $details
    )
    {

    }
}