<?php

namespace Surreal\Abstracts\Connectors\SurrealConnector;

use Surreal\Enums\Auth\AuthMethod;
use Surreal\Enums\Connector\Connector;
use Surreal\Interfaces\Connection;

abstract readonly class SurrealConnector implements Connection
{
    public function __construct(
        private string     $host,
        private int        $port,
        private string     $namespace,
        private string     $database,
        private string     $username,
        private string     $password,
        private AuthMethod $authMethod
    )
    {
    }

    abstract function connect(): void;

    abstract function disconnect(): void;

    abstract function url(): string;
}