<?php

namespace Surreal\abstracts;

abstract readonly class SurrealConnector
{

    /**
     * Establish a connection
     * @return void
     */
    abstract function connect(): void;
}