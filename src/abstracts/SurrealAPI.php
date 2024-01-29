<?php

namespace Surreal\abstracts;

use Surreal\classes\responses\AuthenticationResponse;

abstract class SurrealAPI
{
    /**
     * Returns the status of the connected SurrealDB
     * @return int
     */
    abstract function status(): int;

    /**
     * Returns the health of the connected SurrealDB
     * @return int
     */
    abstract function health(): int;

    /**
     * Outputs the version of the connected SurrealDB
     * @return string
     */
    abstract function version(): string;

    /**
     * Imports data into the pointed database
     * @return string
     */
    abstract function import(): string;

    /**
     * Exports the pointed database
     * @return string
     */
    abstract function export(): string;

    /**
     * Sign in an existing user
     * @return AuthenticationResponse
     */
    abstract function signin(): AuthenticationResponse;

    /**
     * Signs up a new user
     * @return AuthenticationResponse
     */
    abstract function signup(): AuthenticationResponse;

    /**
     *
     */
    abstract function table(): string;
}