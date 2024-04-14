<?php

namespace Surreal\Core\Client;

use Beau\CborPHP\exceptions\CborException;
use Exception;
use Surreal\Cbor\CBOR;
use Surreal\Client\AbstractProtocol;
use Surreal\Core\Rpc\RpcMessage;
use Surreal\Exceptions\RpcException;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\Rpc\RpcMessageErrorResponse;
use Surreal\Responses\Rpc\RpcMessageResponse;
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
	) {
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
		$message = RpcMessage::create("use")->setParams([$target["namespace"], $target["database"]]);
		$result = $this->execute($message);

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
		$message = RpcMessage::create("let")->setParams([$param, $value]);
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function unset(string $param): null
	{
		$message = RpcMessage::create("unset")->setParams([$param]);
		return $this->execute($message);
	}

	/**
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
	 * @throws Exception
	 */
	public function signin(array $params): ?string
	{
		$message = RpcMessage::create("signin")->setParams([$params]);
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function signup(array $params): ?string
	{
		$message = RpcMessage::create("signup")->setParams([$params]);
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function authenticate(string $token): null
	{
		$message = RpcMessage::create("authenticate")->setParams([$token]);
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function info(): ?array
	{
		$message = RpcMessage::create("info");
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function invalidate(): null
	{
		$message = RpcMessage::create("invalidate");
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function select(string $thing): ?array
	{
		$message = RpcMessage::create("select")->setParams([$thing]);
		return $this->execute($message);
	}

	/**
	 * @example $data = [["name" => "some_name"]] or for bulk insert $data = [["name" => "some_name_x"], ["name" => "some_name_y"]]
	 * @throws Exception
	 */
	public function insert(string $thing, array $data): ?array
	{
		$message = RpcMessage::create("insert")->setParams([$thing, $data]);
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function create(string $thing, array $data): ?array
	{
		$message = RpcMessage::create("create")->setParams([$thing, $data]);
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function update(string $thing, array $data): ?array
	{
		$message = RpcMessage::create("update")->setParams([$thing, $data]);
		return $this->execute($message);
	}

	/**
	 * @throws Exception
	 */
	public function merge(string $thing, array $data): ?array
	{
		$message = RpcMessage::create("merge")->setParams([$thing, $data]);
		return $this->execute($message);
	}

	/**
     *
	 * @throws Exception
	 */
	public function patch(string $thing, array $data, bool $diff = false): ?array
	{
		$message = RpcMessage::create("patch")->setParams([$thing, $data, $diff]);
		return $this->execute($message);
	}

	/**
	 * Removes a table or a single record from a table
	 * @throws Exception
	 */
	public function delete(string $thing): ?array
	{
		$message = RpcMessage::create("delete")->setParams([$thing]);
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
     * Creates a payload from the message
     * @param RpcMessage $message
     * @return string
     * @throws CborException
     */
	private function createPayload(RpcMessage $message): string
	{
		return CBOR::encode($message->toAssoc());
	}

	/**
	 * @throws Exception
	 */
	private function execute(RpcMessage $message): mixed
	{
		$id = $this->incrementalId++;
		$payload = $this->createPayload($message->setId($id));

		$this->client->text($payload);

		// This reads the response from the websocket
		// Blocking the main thread until the response is received.
		// This ensures that the response is received in the order it was sent.

		while ($result = $this->client->receive()) {
			$content = $result->getContent();

			if ($content === "") {
				continue;
			}

			$content = CBOR::decode($result);

			if ($content["id"] === $id) {
                $response = ResponseInterface::resolve($content);

                return match (get_class($response)) {
                    RpcMessageResponse::class => $response->result,
                    RpcMessageErrorResponse::class => throw new RpcException($response->error),
                    default => throw new Exception("Invalid response")
                };
			}
		}

        throw new Exception("No response received");
	}

	public function getTimeout(): int
	{
		return $this->client->getTimeout();
	}
}
