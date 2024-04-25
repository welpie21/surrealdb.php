<?php

namespace Surreal\Core\Client;

use Exception;
use Surreal\Cbor\CBOR;
use Surreal\Core\AbstractSurreal;
use Surreal\Core\Responses\Types\RpcResponse;
use Surreal\Core\Results\RpcResult;
use Surreal\Core\Rpc\RpcMessage;
use Surreal\Core\Utils\ThingParser;
use Surreal\Curl\HttpContentType;
use WebSocket\Client as WebsocketClient;
use WebSocket\Middleware\{CloseHandler, PingResponder};

class SurrealWebsocket extends AbstractSurreal
{
    private WebsocketClient $client;
    private int $incrementalId = 0;

    /**
     * @param string $host
     * @param array{namespace:string, database:string|null} $target
     * @throws Exception
     */
    public function __construct(
        string $host,
        array  $target = []
    )
    {
        $this->client = (new WebsocketClient($host))
            ->addMiddleware(new CloseHandler())
            ->addMiddleware(new PingResponder())
            ->addHeader("Sec-WebSocket-Protocol", "cbor")
            ->setTimeout(5);

        $this->client->connect();

        $this->use($target);

        parent::__construct($host, $target);
    }

    /**
     * Set the namespace and database to be used for the following queries in the current open connection.
     * @param array{namespace:string|null,database:string|null} $target
     * @return null
     * @throws Exception
     */
    public function use(array $target): null
    {
        // if this throws exception, the code after it will not run
        // So we are ensuring that the namespace and database are set correctly.
        $message = RpcMessage::create("use")->setParams([$target["namespace"], $target["database"]]);
        $result = $this->execute($message);

        parent::use($target);

        return $result;
    }

    /**
     * Check if the websocket connection is open.
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->client->isConnected();
    }

    /**
     * Set the timeout for the websocket connection in seconds.
     * @param int $seconds
     * @return void
     */
    public function setTimeout(int $seconds): void
    {
        $this->client->setTimeout($seconds);
    }

    /**
     * Set a new parameter.
     * @throws Exception
     */
    public function let(string $param, string $value): null
    {
        $message = RpcMessage::create("let")->setParams([$param, $value]);
        return $this->execute($message);
    }

    /**
     * Dismisses a previously set parameter
     * @throws Exception
     */
    public function unset(string $param): null
    {
        $message = RpcMessage::create("unset")->setParams([$param]);
        return $this->execute($message);
    }

    /**
     * Query a raw SurrealQL query
     * @param string $sql
     * @param array|null $vars
     * @return mixed
     * @throws Exception
     */
    public function query(string $sql, ?array $vars = null): mixed
    {
        $message = RpcMessage::create("query")->setParams([$sql, $vars]);
        return $this->execute($message);
    }

    /**
     * Signin with a root, namespace, database or scoped user.
     * @throws Exception
     */
    public function signin(array $params): ?string
    {
        $message = RpcMessage::create("signin")->setParams([$params]);
        return $this->execute($message);
    }

    /**
     * Signup a new scoped user
     * @throws Exception
     */
    public function signup(array $params): ?string
    {
        $message = RpcMessage::create("signup")->setParams([$params]);
        return $this->execute($message);
    }

    /**
     * Authenticates the current session with the given token
     * @throws Exception
     */
    public function authenticate(string $token): null
    {
        $message = RpcMessage::create("authenticate")->setParams([$token]);
        return $this->execute($message);
    }

    /**
     * Returns auth information of the current session
     * @throws Exception
     */
    public function info(): ?array
    {
        $message = RpcMessage::create("info");
        return $this->execute($message);
    }

    /**
     * Makes the current session invalid
     * @throws Exception
     */
    public function invalidate(): null
    {
        $message = RpcMessage::create("invalidate");
        return $this->execute($message);
    }

    /**
     * Select a whole table or a single record from a table
     * @throws Exception
     */
    public function select(string $thing): ?array
    {
        $thing = ThingParser::from($thing)->value;
        $message = RpcMessage::create("select")->setParams([$thing]);
        return $this->execute($message);
    }

    /**
     * @throws Exception
     * @example $data = [["name" => "some_name"]] or for bulk insert $data = [["name" => "some_name_x"], ["name" => "some_name_y"]]
     */
    public function insert(string $table, array $data): ?array
    {
        $table = ThingParser::from($table)->getTable();
        $message = RpcMessage::create("insert")->setParams([$table, $data]);
        return $this->execute($message);
    }

    /**
     * Creates a new record inside a table with the given data
     * @throws Exception
     */
    public function create(string $thing, array $data): ?array
    {
        $thing = ThingParser::from($thing)->value;
        $message = RpcMessage::create("create")->setParams([$thing, $data]);
        return $this->execute($message);
    }

    /**
     * Updates a record inside a table with the given data. When you don't want to overwrite the record, use merge instead.
     * @throws Exception
     */
    public function update(string $thing, array $data): ?array
    {
        $thing = ThingParser::from($thing)->value;
        $message = RpcMessage::create("update")->setParams([$thing, $data]);
        return $this->execute($message);
    }

    /**
     * Selectively updates a record inside a table with the given data.
     * @throws Exception
     */
    public function merge(string $thing, array $data): ?array
    {
        $thing = ThingParser::from($thing)->value;
        $message = RpcMessage::create("merge")->setParams([$thing, $data]);
        return $this->execute($message);
    }

    /**
     * Patches a specified column inside a record with the given value.
     * @param array{op:string,path:string,value:mixed} $data
     * @throws Exception
     */
    public function patch(string $thing, array $data, bool $diff = false): ?array
    {
        $thing = ThingParser::from($thing)->value;
        $message = RpcMessage::create("patch")->setParams([$thing, $data, $diff]);
        return $this->execute($message);
    }

    /**
     * Removes a table or a single record from a table
     * @throws Exception
     */
    public function delete(string $thing): ?array
    {
        $thing = ThingParser::from($thing)->value;
        $message = RpcMessage::create("delete")->setParams([$thing]);
        return $this->execute($message);
    }

    /**
     * Runs a surrealdb function with the given arguments
     * @throws Exception
     */
    public function run(string $func, ?string $version, ...$args): mixed
    {
        $message = RpcMessage::create("run")->setParams([$func, $version, $args]);
        return $this->execute($message);
    }

    /**
     * Closes the websocket connection
     * @return void
     */
    public function close(): void
    {
        $this->client->close();
    }

    /**
     * @throws Exception
     */
    private function execute(RpcMessage $message): mixed
    {
        $id = $this->incrementalId++;
        $payload = $message->setId($id)->toCborString();

        $this->client->binary($payload);

        // This reads the response from the websocket
        // Blocking the main thread until the response is received.
        // This ensures that the response is received in the order it was sent.

        while ($result = $this->client->receive()) {
            $content = $result->getContent();

            if ($content === "") {
                continue;
            }

            $content = CBOR::decode($content);

            if ($content["id"] === $id) {
                $response = RpcResponse::from($content, HttpContentType::CBOR, 200);
                return RpcResult::from($response);
            }
        }

        throw new Exception("No response received");
    }

    public function getTimeout(): int
    {
        return $this->client->getTimeout();
    }
}
