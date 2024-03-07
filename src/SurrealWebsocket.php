<?php

namespace Surreal;

use Closure;
use Exception;
use Surreal\abstracts\AbstractProtocol;
use Surreal\classes\ResponseParser;
use Surreal\classes\responses\WebsocketResponse;
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
            ->addMiddleware(new PingResponder())
            ->setTimeout(5)
            ->setPersistent(true);

        $this->client->connect();

        parent::__construct($host, $target);
    }

    /**
     * @param array{namespace:string|null,database:string|null} $target
     * @return void
     * @throws Exception
     */
    #[\Override]
    public function use(array $target): void
    {
        $this->execute(
            method: "use",
            params: [$target["namespace"], $target["database"]]
        );

        parent::use($target);
    }

    public function isConnected(): bool
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

    /**
     * @throws Exception
     */
    public function let(string $param, string $value): mixed
    {
        return $this->execute(
            method: "let",
            params: [$param, $value]
        );
    }

    /**
     * @throws Exception
     */
    public function unset(string $param): mixed
    {
        return $this->execute(
            method: "unset",
            params: [$param]
        );
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function query(string $sql, array $params): mixed
    {
        return $this->execute(
            method: "query",
            params: [$sql, $params]
        );
    }

    /**
     * @throws Exception
     */
    public function signin(array $params): mixed
    {
        return $this->execute(
            method: "signin",
            params: [$params]
        );
    }

    /**
     * @throws Exception
     */
    public function signup(array $params): mixed
    {
        return $this->execute(
            method: "signup",
            params: [$params]
        );
    }

    /**
     * @throws Exception
     */
    public function authenticate(string $token): mixed
    {
        return $this->execute(
            method: "authenticate",
            params: [$token]
        );
    }

    /**
     * @throws Exception
     */
    public function info(): array
    {
        return $this->execute("info");
    }

    /**
     * @throws Exception
     */
    public function invalidate(): array
    {
        return $this->execute("invalidate");
    }

    /**
     * @throws Exception
     */
    public function select(string $thing): array
    {
        return $this->execute(
            method: "select",
            params: [$thing]
        );
    }

    /**
     * @throws Exception
     */
    public function insert(string $thing, array $data): array
    {
        return $this->execute(
            method: "insert",
            params: [$thing, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function create(string $table, array $data): array
    {
        return $this->execute(
            method: "create",
            params: [$table, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function update(string $table, array $data): array
    {
        return $this->execute(
            method: "update",
            params: [$table, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function merge(string $table, array $data): array
    {
        return $this->execute(
            method: "merge",
            params: [$table, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function patch(string $table, array $data, bool $diff = false): array
    {
        return $this->execute(
            method: "patch",
            params: [$table, $data, $diff]
        );
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): array
    {
        return $this->execute(
            method: "delete",
            params: [$thing]
        );
    }

    public function close(): void
    {
        $this->client->close();
    }

    /**
     * @throws Exception
     */
    private function execute(string $method, ?array $params = null): mixed
    {
        $payload = [
            "id" => 1,
            "method" => $method
        ];

        if ($params !== null) {
            $payload["params"] = $params;
        }

        $response = $this->client->text(json_encode($payload));

        $result = $response->getContent();
        $result = json_decode($result, true);

        $parser = new ResponseParser($result);

        /** @var WebsocketResponse $result */
        $result = $parser->getResponse();

        if(!($result instanceof WebsocketResponse)) {
            throw new Exception("Something went wrong with parsing the response");
        }

        return $result->result;
    }
}