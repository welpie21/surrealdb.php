<?php

namespace Surreal\abstracts;

abstract readonly class SurrealDisposable
{
    /**
     * disposes the current connection
     * @param SurrealConnector $connection
     * @return void
     */
    abstract function dispose(SurrealConnector $connection): void;
}