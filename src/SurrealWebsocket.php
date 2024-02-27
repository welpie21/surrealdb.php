<?php

namespace Surreal;

use Closure;
use Surreal\abstracts\AbstractProtocol;
use WebSocket\Client as WebsocketClient;
use WebSocket\Middleware\{CloseHandler, PingResponder};

class SurrealWebsocket extends AbstractProtocol
{
    private WebsocketClient $client;

    /**
     * @param string $host
     * @param array{namespace:string, database:string|null} $target
     */
    public function __construct(
        string $host,
        array  $target = []
    )
    {
        $this->client = (new WebsocketClient($host))
            ->addMiddleware(new CloseHandler())
            ->addMiddleware(new PingResponder());

        parent::__construct($host, $target);
    }

    #[\Override]
    public function use(array $target): void
    {
        parent::use($target);
    }

    public function isConnected(): int
    {
        return $this->client->isConnected();
    }

    public function setTimeout(int $seconds): Closure
    {
        $reset = function () {
            $timeout = $this->client->getTimeout();
            $this->client->setTimeout($timeout);
        };

        $this->client->setTimeout($seconds);

        return $reset;
    }

    public function let(array $params): void
    {
        $this->client->binary("");
    }

    public function unset(array $params): void
    {
        $this->client->binary("");
    }

    public function query(string $sql, array $params): void
    {
        
    }

    public function signin(array $params): void
    {

    }

    public function signup(array $params): void
    {

    }

    public function authenticate(array $params): void
    {

    }

    public function info(): array
    {
        return [];
    }

    public function invalidate(): array
    {

    }

    public function select(string $id): array
    {
        return [];
    }

    public function insert(string $table, array $data): array
    {

    }

    public function create(string $table, array $data): array
    {

    }

    public function update(string $table, array $data): array
    {

    }

    public function merge(string $table, array $data): array
    {

    }

    public function patch(string $table, array $data): array
    {

    }

    public function delete(string $thing): array
    {

    }


    public function close(): void
    {
        $this->client->close();
    }
}