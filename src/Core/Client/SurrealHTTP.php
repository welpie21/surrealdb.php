<?php

namespace Surreal\Core\Client;

use Beau\CborPHP\exceptions\CborException;
use CurlHandle;
use Exception;
use src\Curl\HttpContentType;
use src\Curl\HTTPMethod;
use Surreal\Core\AbstractSurreal;
use Surreal\Core\Results\AuthResult;
use Surreal\Core\Results\RpcResult;
use Surreal\Core\Rpc\RpcMessage;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\RpcResponse;


class SurrealHTTP extends AbstractSurreal
{
    private int $incrementalId = 0;
    private ?CurlHandle $client;

    /**
     * @param string $host
     * @param array{namespace:string, database:string|null} $target
     * @param array $options - curl options.
     * @codeCoverageIgnore - Being used but false positive.
     */
    public function __construct(
        string $host,
        array  $target = [],
        array  $options = []
    )
    {
        // initialize the curl client.
        $this->client = curl_init();

        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->client, CURLOPT_TIMEOUT, 5);

        curl_setopt_array($this->client, $options);

        parent::__construct($host, $target);
    }

    /**
     * Returns the status of the server.
     * @throws Exception
     */
    public function status(): int
    {
        return $this->checkStatusCode("/status");
    }

    /**
     * Returns the health status of the server.
     * @throws Exception
     */
    public function health(): int
    {
        return $this->checkStatusCode("/health");
    }

    /**
     * Returns the version of the server.
     * @throws Exception
     */
    public function version(): string
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::GET,
            response: RpcResponse::class,
            options: [
                CURLOPT_POSTFIELDS => RpcMessage::create("version")
                    ->setId($this->incrementalId++)
                    ->toCborString()
            ]
        );

        return RpcResult::from($response);
    }

    /**
     * @return array|null - Array of SingleRecordResponse
     * @throws Exception
     */
    public function import(string $content, string $username, string $password): ?array
    {
        /** @var AnyResponse|string $response */
        $response = $this->execute(
            endpoint: "/import",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_ACCEPT,
                    "Content-Type: text/plain",
                    "Core-NS: " . $this->getNamespace(),
                    "Core-DB: " . $this->getDatabase()
                ],
                CURLOPT_POSTFIELDS => $content,
                CURLOPT_USERPWD => "$username:$password"
            ]
        );

        return ExportResponse::from($response);
    }

    /**
     * @throws Exception
     */
    public function export(string $username, string $password): string
    {
        $response = $this->execute(
            endpoint: "/export",
            method: HTTPMethod::GET,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_ACCEPT,
                    "Core-NS: " . $this->getNamespace(),
                    "Core-DB: " . $this->getDatabase()
                ],
                CURLOPT_USERPWD => "$username:$password"
            ]
        );

        return ExportResponse::from($response);
    }

    /**
     * @throws Exception
     */
    public function signin(array $data): ?string
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            response: RpcResponse::class,
            options: [
                CURLOPT_HTTPHEADER => AuthResult::requiredHTTPHeaders($this),
                CURLOPT_POSTFIELDS => RpcMessage::create("signin")
                    ->setId($this->incrementalId++)
                    ->setParams($data)
                    ->toCborString()
            ]
        );

        return AuthResult::from($response);
    }

    /**
     * @throws Exception
     */
    public function signup(array $data): ?string
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            response: RpcResponse::class,
            options: [
                CURLOPT_HTTPHEADER => AuthResult::requiredHTTPHeaders($this),
                CURLOPT_POSTFIELDS => RpcMessage::create("signup")
                    ->setId($this->incrementalId++)
                    ->setParams($data)
                    ->toCborString()
            ]
        );

        return AuthResult::from($response);
    }

    /**
     * @param string $table
     * @param mixed $data
     * @return object|null
     * @throws Exception
     */
    public function create(string $table, mixed $data): ?array
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            response: RpcResponse::class,
            options: [
                CURLOPT_HTTPHEADER => RpcResult::requiredHTTPHeaders($this),
                CURLOPT_POSTFIELDS => RpcMessage::create("create")
                    ->setId($this->incrementalId++)
                    ->setParams([$table, $data])
                    ->toCborString()
            ]
        );

        return RpcResult::from($response);
    }

    /**
     * @param string $thing
     * @param mixed $data
     * @return object|null
     * @throws Exception
     */
    public function update(string $thing, mixed $data): ?array
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::PUT,
            response: RpcResponse::class,
            options: [
                CURLOPT_HTTPHEADER => RpcResponse::requiredHTTPHeaders($this),
                CURLOPT_POSTFIELDS => RpcMessage::create("update")
                    ->setId($this->incrementalId++)
                    ->setParams([$thing, $data])
                    ->toCborString()
            ]
        );

        return RpcResult::from($response);
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, mixed $data): ?array
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::PATCH,
            response: RpcResponse::class,
            options: [
                CURLOPT_HTTPHEADER => RpcResult::requiredHTTPHeaders($this),
                CURLOPT_POSTFIELDS => RpcMessage::create("merge")
                    ->setId($this->incrementalId++)
                    ->setParams([$thing, $data])
                    ->toCborString()
            ]
        );

        return RpcResult::from($response);
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): ?array
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            response: RpcResponse::class,
            options: [
                CURLOPT_HTTPHEADER => RpcResult::requiredHTTPHeaders($this),
                CURLOPT_POSTFIELDS => RpcMessage::create("delete")
                    ->setId($this->incrementalId++)
                    ->setParams([$thing])
                    ->toCborString()
            ]
        );
    }

    /**
     * Execute a SQL query.
     * @param string $query
     * @param array $params
     * @return array|null
     * @throws CborException
     * @throws Exception
     */
    public function query(string $query, array $params = []): ?array
    {
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            response: RpcResponse::class,
            options: [
                CURLOPT_HTTPHEADER => RpcResult::requiredHTTPHeaders($this),
                CURLOPT_POSTFIELDS => RpcMessage::create("query")
                    ->setId($this->incrementalId++)
                    ->setParams([$query, $params])
                    ->toCborString()
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function close(): void
    {
        if ($this->client === null) {
            throw new Exception("The database connection is already closed.");
        }

        $this->auth->setToken(null);

        curl_close($this->client);
        $this->client = null;
    }

    /**
     * @throws Exception
     */
    private function baseExecute(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): void
    {
        if ($this->client === null) {
            throw new Exception("The curl client is not initialized.");
        }

        curl_setopt($this->client, CURLOPT_URL, $this->host . $endpoint);
        curl_setopt($this->client, CURLOPT_CUSTOMREQUEST, $method->value);

        curl_setopt_array($this->client, $options);

        // throwing an exception if the request fails.
        if (curl_exec($this->client) === false) {
            throw new Exception(curl_error($this->client));
        }
    }

    /**
     * @param string $endpoint
     * @param HTTPMethod $method
     * @param string $response
     * @param array $options
     * @return ResponseInterface
     * @throws Exception
     */
    private function execute(
        string     $endpoint,
        HTTPMethod $method,
        string     $response,
        array      $options = []
    ): ResponseInterface
    {
        $this->baseExecute($endpoint, $method, $options);

        // get the content type of the response.
        $type = curl_getinfo($this->client, CURLINFO_CONTENT_TYPE);
        $status = curl_getinfo($this->client, CURLINFO_RESPONSE_CODE);
        $body = curl_multi_getcontent($this->client);

        $type = HttpContentType::from($type);

        /** @var $response ResponseInterface */
        return $response::from($type, $body, $status);
    }

    /**
     * Executes a request without expecting a response.
     * uses the health, status endpoints.
     * @throws Exception
     */
    private function checkStatusCode(string $endpoint): int
    {
        $this->baseExecute($endpoint, HTTPMethod::GET);
        return curl_getinfo($this->client, CURLINFO_RESPONSE_CODE);
    }
}
