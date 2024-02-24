<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;

class AuthResponse extends AbstractResponse
{
    const KEYS = ["code", "details", "token"];

    /**
     * @param array{code: string, details: string, token: string} $response
     * @return AbstractResponse
     */
    public static function parse(array $response): AbstractResponse
    {
        return new AuthResponse($response);
    }
}