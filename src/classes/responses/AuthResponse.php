<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;
use Override;

class AuthResponse extends AbstractResponse
{
    const array KEYS = ["code", "details", "token"];

    /**
     * @return array{code: int, details: string, token: string}
     */
    #[Override] public function getData(): array
    {
        return parent::getData();
    }
}