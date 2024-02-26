<?php

namespace Surreal\abstracts;

abstract class AbstractAuth
{
    protected ?string $token = null;

    /**
     * Set the auth token
     * @param string|null $token
     * @return void
     */
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}