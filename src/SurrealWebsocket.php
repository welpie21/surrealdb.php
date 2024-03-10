<?php

namespace Surreal;

use Exception;
use Surreal\abstracts\AbstractProtocol;
use Surreal\classes\ResponseParser;
use Surreal\classes\responses\WebsocketResponse;
use WebSocket\Client as WebsocketClient;
use WebSocket\Middleware\{CloseHandler, PingResponder};

class SurrealWebsocket extends AbstractProtocol
{
    private WebsocketClient $client;
    private int $incrementalId = 0;

    /**
     * @param string $host
     * @param array{namespace:string, database:string|null} $target
     * @throws Exception
     * @codeCoverageIgnore - Being used but false positive.
     */
    public function __construct(
        string $host,
        array  $target = []
    )
    {
        $this->client = (new WebsocketClient($host))
            ->addMiddleware(new CloseHandler())
            ->addMiddleware(new PingResponder())
            ->setTimeout(5);

        $this->client->connect();
        $this->use($target);

        parent::__construct($host, $target);
    }

    /**
     * @param array{namespace:string|null,database:string|null} $target
     * @return null
     * @throws Exception
     */
    public function use(array $target): null
    {
        // if this throws exception, the code after it will not run
        // So we are ensuring that the namespace and database are set correctly.

        $result = $this->execute(
            method: "use",
            params: [$target["namespace"], $target["database"]]
        );

        parent::use($target);

        return $result;
    }

    public function isConnected(): bool
    {
        return $this->client->isConnected();
    }

    /**
     * @param int $seconds
     * @return void
     */
    public function setTimeout(int $seconds): void
    {
        $this->client->setTimeout($seconds);
    }

    /**
     * @throws Exception
     */
    public function let(string $param, string $value): null
    {
        return $this->execute(
            method: "let",
            params: [$param, $value]
        );
    }

    /**
     * @throws Exception
     */
    public function unset(string $param): null
    {
        return $this->execute(
            method: "unset",
            params: [$param]
        );
    }

    /**
     * @param string $sql
     * @param array|null $vars
     * @return mixed
     * @throws Exception
     */
    public function query(string $sql, ?array $vars = null): mixed
    {
        return $this->execute(
            method: "query",
            params: $vars ? [$sql, $vars] : [$sql]
        );
    }

    /**
     * @throws Exception
     */
    public function signin(array $params): ?string
    {
        return $this->execute(
            method: "signin",
            params: [$params]
        );
    }

    /**
     * @throws Exception
     */
    public function signup(array $params): ?string
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
    public function info(): ?array
    {
        return $this->execute("info");
    }

    /**
     * @throws Exception
     */
    public function invalidate(): null
    {
        return $this->execute("invalidate");
    }

    /**
     * @throws Exception
     */
    public function select(string $thing): ?array
    {
        return $this->execute(
            method: "select",
            params: [$thing]
        );
    }

    /**
     * @example $data = [["name" => "some_name"]] or for bulk insert $data = [["name" => "some_name_x"], ["name" => "some_name_y"]]
     * @throws Exception
     */
    public function insert(string $thing, array $data): ?array
    {
        return $this->execute(
            method: "insert",
            params: [$thing, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function create(string $thing, array $data): ?array
    {
        return $this->execute(
            method: "create",
            params: [$thing, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function update(string $thing, array $data): ?array
    {
        return $this->execute(
            method: "update",
            params: [$thing, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, array $data): ?array
    {
        return $this->execute(
            method: "merge",
            params: [$thing, $data]
        );
    }

    /**
     * @throws Exception
     */
    public function patch(string $thing, array $data, bool $diff = false): ?array
    {
        return $this->execute(
            method: "patch",
            params: [$thing, $data, $diff]
        );
    }

    /**
     * Removes a table or a single record from a table
     * @throws Exception
     */
    public function delete(string $thing): ?array
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
     * @param int $id
     * @param string $method
     * @param array|null $params
     * @return string
     */
    private function createPayload(
        int    $id,
        string $method,
        ?array $params
    ): string
    {
        $payload = [
            "id" => $id,
            "method" => $method
        ];

        if ($params !== null) {
            $payload["params"] = $params;
        }

        return json_encode($payload);
    }

    /**
     * @throws Exception
     */
    private function execute(
        string $method,
        ?array $params = []
    ): mixed
    {
        $id = $this->incrementalId++;

        $payload = $this->createPayload($id, $method, $params);

        $this->client->text($payload);

        // This reads the response from the websocket
        // Blocking the main thread until the response is received.
        // This ensures that the response is received in the order it was sent.

        while ($result = $this->client->receive()) {
            $content = $result->getContent();

            if($content === "") {
                return null;
            }

            $content = json_decode($content, true);

            if ($content["id"] === $id) {
                /** @var WebsocketResponse $response */
                $response = ResponseParser::create($content);
                return $response->result;
            }
        }

        return null;
    }

    public function getTimeout(): int
    {
        return $this->client->getTimeout();
    }
}