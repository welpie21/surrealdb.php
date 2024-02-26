<?php

namespace Surreal\classes\protocols;

use Surreal\abstracts\AbstractSurreal;

class SurrealWebsocket extends AbstractSurreal
{
    /**
     * @param string $host
     * @param array{namespace:string, database:string|null} $target
     */
    public function __construct(
        string $host,
        array  $target = []
    )
    {
        parent::__construct($host, $target);
    }

    public function connect()
    {

    }

    public function status(): int
    {
        // TODO: Implement status() method.
    }

    public function version(): ?string
    {
        // TODO: Implement version() method.
    }

    public function import(string $content, string $username, string $password): string
    {
        // TODO: Implement import() method.
    }

    public function export(string $username, string $password): string
    {
        // TODO: Implement export() method.
    }

    public function signin(mixed $data): ?string
    {
        // TODO: Implement signin() method.
    }

    public function signup(mixed $data): ?string
    {
        // TODO: Implement signup() method.
    }

    public function create(string $table, mixed $data): ?object
    {
        // TODO: Implement create() method.
    }

    public function update(string $thing, mixed $data): ?object
    {
        // TODO: Implement update() method.
    }

    public function merge(string $thing, mixed $data): ?object
    {
        // TODO: Implement merge() method.
    }

    public function delete(string $thing): ?object
    {
        // TODO: Implement delete() method.
    }

    public function sql(string $query): array|object|null
    {
        // TODO: Implement sql() method.
    }
}
