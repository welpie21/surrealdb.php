<?php

namespace Surreal;

use Exception;
use Surreal\abstracts\AbstractProtocol;
use Surreal\classes\exceptions\SurrealException;
use Surreal\classes\protocols\SurrealHTTP;
use Surreal\classes\protocols\SurrealWebsocket;

readonly class Surreal
{
    private SurrealHTTP|SurrealWebsocket $protocol;

    /**
     * @param array{namespace:string|null, database:string|null, scope:string|null} $target
     * @throws Exception
     */
    public function __construct(
        string $host,
        array  $target = [],
    )
    {
        $this->protocol = match (parse_url($host, PHP_URL_SCHEME)) {
            "http", "https" => new SurrealHTTP($host, $target),
            "ws", "wss" => new SurrealWebsocket($host, $target),
            default => throw new SurrealException("Unsupported protocol.")
        };
    }

    public function status(): int
    {
        return $this->protocol->status();
    }

    /**
     * @throws Exception
     */
    public function version(): ?string
    {
        return $this->protocol->version();
    }

    /**
     * @throws Exception
     */
    public function import(string $content, string $username, string $password): string
    {
        return $this->protocol->import($content, $username, $password);
    }

    /**
     * @throws Exception
     */
    public function export(string $username, string $password): string
    {
        return $this->protocol->export($username, $password);
    }

    /**
     * @throws Exception
     */
    public function signin(mixed $data): ?string
    {
        return $this->protocol->signin($data);
    }

    /**
     * @throws Exception
     */
    public function signup(mixed $data): ?string
    {
        return $this->protocol->signup($data);
    }

    /**
     * @throws Exception
     */
    public function create(string $table, mixed $data): ?object
    {
        return $this->protocol->create($table, $data);
    }

    /**
     * @throws Exception
     */
    public function update(string $thing, mixed $data): ?object
    {
        return $this->protocol->update($thing, $data);
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, mixed $data): ?object
    {
        return $this->protocol->merge($thing, $data);
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): ?object
    {
        return $this->protocol->delete($thing);
    }

    /**
     * @throws Exception
     */
    public function sql(string $query, ?array $params = null): array|object|null
    {
        if ($this->protocol instanceof SurrealHTTP && $params !== null) {
            throw new SurrealException("Params are not supported in HTTP protocol.");
        }

        return $this->protocol->sql($query, $params);
    }
}