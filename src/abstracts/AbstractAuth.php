<?php

namespace Surreal\abstracts;

abstract class AbstractAuth extends AbstractTarget
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

    /**
     * Validate if the token is valid.
     * @return bool
     */
    abstract public function validate(): bool;
}