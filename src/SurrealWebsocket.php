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

    public function close(): void
    {
        $this->client->close();
    }
}
