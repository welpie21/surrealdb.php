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
     */
    #[\Override]
    public function use(array $target): void
    {
        $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "use",
                "params" => [$target["namespace"], $target["database"]]
            ])
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

    public function let(string $param, string $value): void
    {
        $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "let",
                "params" => [$param, $value]
            ])
        );
    }

    public function unset(string $param): void
    {
        $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "unset",
                "params" => [$param]
            ])
        );
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function query(string $sql, array $params): mixed
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "query",
                "params" => [$sql, $params]
            ])
        );

        $result = $response->getContent();
        return json_decode($result);
    }

    /**
     * @throws Exception
     */
    public function signin(array $params): mixed
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "signin",
                "params" => [$params]
            ])
        );

        $result = $response->getContent();
        $result = json_decode($result);

        $parser = new ResponseParser($result);

        /** @var WebsocketResponse $result */
        $result = $parser->getResponse();

        return $result->result;
    }

    /**
     * @throws Exception
     */
    public function signup(array $params): mixed
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "signup",
                "params" => [$params]
            ])
        );

        $result = $response->getContent();
        $result = json_decode($result);

        $parser = new ResponseParser($result);

        /** @var WebsocketResponse $result */
        $result = $parser->getResponse();

        return $result->result;
    }

    /**
     * @throws Exception
     */
    public function authenticate(string $token): mixed
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "authenticate",
                "params" => [$token]
            ])
        );

        $result = $response->getContent();
        $result = json_decode($result);

        $parser = new ResponseParser($result);

        /** @var WebsocketResponse $result */
        $result = $parser->getResponse();

        return $result->result;
    }

    /**
     * @throws Exception
     */
    public function info(): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "info"
            ])
        );

        $result = $response->getContent();
        $result = json_decode($result);

        $parser = new ResponseParser($result);

        /** @var WebsocketResponse $result */
        $result = $parser->getResponse();

        return $result->result;
    }

    /**
     * @throws Exception
     */
    public function invalidate(): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "invalidate"
            ])
        );

        $result = $response->getContent();
        $result = json_decode($result);

        $parser = new ResponseParser($result);

        /** @var WebsocketResponse $result */
        $result = $parser->getResponse();

        return $result->result;
    }

    /**
     * @throws Exception
     */
    public function select(string $thing): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "select",
                "params" => [$thing]
            ])
        );

        $result = $response->getContent();
        $result = json_decode($result);

        $parser = new ResponseParser($result);

        /** @var WebsocketResponse $result */
        $result = $parser->getResponse();

        return $result->result;
    }

    public function insert(string $thing, array $data): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "insert",
                "params" => [$thing, $data]
            ])
        );

        return [];
    }

    public function create(string $table, array $data): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "create",
                "params" => [$table, $data]
            ])
        );

        return [];
    }

    public function update(string $table, array $data): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "update",
                "params" => [$table, $data]
            ])
        );

        return [];
    }

    public function merge(string $table, array $data): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "merge",
                "params" => [$table, $data]
            ])
        );

        return [];
    }

    public function patch(string $table, array $data, bool $diff = false): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "patch",
                "params" => [$table, $data, $diff]
            ])
        );

        return [];
    }

    public function delete(string $thing): array
    {
        $response = $this->client->text(
            json_encode([
                "id" => 1,
                "method" => "delete",
                "params" => [$thing]
            ])
        );

        return [];
    }


    public function close(): void
    {
        $this->client->close();
    }
}