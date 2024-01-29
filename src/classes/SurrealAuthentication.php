<?php

namespace Surreal\classes;

use Surreal\enums\AuthMode;

readonly class SurrealAuthentication
{
    public function __construct(
        public string $username,
        public string $password,
        public AuthMode $mode
    )
    { }
}