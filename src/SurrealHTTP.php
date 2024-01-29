<?php

namespace Surreal;

use Surreal\abstracts\SurrealAPI;

class SurrealHTTP extends SurrealAPI
{

    #[\Override] function connect(): void
    {
        // TODO: Implement connect() method.
    }

    #[\Override] function status(): int
    {
        // TODO: Implement status() method.
    }

    #[\Override] function health(): int
    {
        // TODO: Implement health() method.
    }

    #[\Override] function version(): string
    {
        // TODO: Implement version() method.
    }

    #[\Override] function import(): string
    {
        // TODO: Implement import() method.
    }

    #[\Override] function export(): string
    {
        // TODO: Implement export() method.
    }

    #[\Override] function signin(): object
    {
        // TODO: Implement signin() method.
    }

    #[\Override] function signup(): object
    {
        // TODO: Implement signup() method.
    }

    #[\Override] function invalidate(): void
    {
        // TODO: Implement invalidate() method.
    }

    #[\Override] function create(string $thing, mixed $data): object|null
    {
        // TODO: Implement create() method.
    }

    #[\Override] function update(string $thing, mixed $data): object|null
    {
        // TODO: Implement update() method.
    }

    #[\Override] function merge(string $thing, mixed $data): object|null
    {
        // TODO: Implement merge() method.
    }

    #[\Override] function delete(string $thing): object|null
    {
        // TODO: Implement delete() method.
    }
}