<?php

namespace Surreal\classes;

use Surreal\abstracts\SurrealConnector;
use Surreal\interfaces\ISurrealQuery;

readonly class SurrealDB extends SurrealConnector implements ISurrealQuery
{
    private readonly string $host;
    private readonly string $user;
    private readonly string $password;
    

    public function __construct()
    {
        
    }

    #[\Override] function query(string $query): object|null
    {
        // TODO: Implement query() method.
    }

    #[\Override] function create(object $data): object|null
    {
        // TODO: Implement create() method.
    }

    #[\Override] function delete(string $record): object|null
    {
        // TODO: Implement delete() method.
    }

    #[\Override] function update(string $record, object $data): object|null
    {
        // TODO: Implement update() method.
    }

    #[\Override] function upsert(?string $record): object|null
    {
        // TODO: Implement upsert() method.
    }

    #[\Override] function connect(): void
    {
        // TODO: Implement connect() method.
    }

    #[\Override] function dispose(SurrealConnector $connection): void
    {
        // TODO: Implement dispose() method.
    }
}