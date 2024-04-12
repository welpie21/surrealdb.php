<?php

namespace Surreal\Client\Http;

use CurlHandle;
use Exception;
use RuntimeException;
use Surreal\abstracts\AbstractProtocol;
use Surreal\Cbor\CBOR;
use Surreal\Cbor\Types\RecordId;
use Surreal\Client\Http\Enums\HTTPMethod;
use Surreal\Exceptions\SurrealAuthException;
use Surreal\Exceptions\SurrealException;
use Surreal\Responses\AnyResponse;
use Surreal\Responses\Auth\AuthResponse;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\ResponseParser;
use Surreal\Rpc\RpcMessage;

const HTTP_ACCEPT = "Accept: application/cbor";
const HTTP_CONTENT_TYPE = "Content-Type: application/cbor";

class SurrealHTTP extends AbstractProtocol
{
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
     */
    public function status(): int
    {
        return $this->checkStatusCode(
            endpoint: "/status",
            method: HTTPMethod::GET
        );
    }

    /**
     * Returns the health status of the server.
     */
    public function health(): int
    {
        return $this->checkStatusCode(
            endpoint: "/health",
            method: HTTPMethod::GET
        );
    }

    /**
     * Returns the version of the server.
     * @throws Exception
     */
    public function version(): ?string
    {
        return $this->execute(
            endpoint: "/version",
            method: HTTPMethod::GET
        );
    }

    /**
     * @return array|null - Array of SingleRecordResponse
     * @throws SurrealException
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
                    "Surreal-NS: " . $this->getNamespace(),
                    "Surreal-DB: " . $this->getDatabase()
                ],
                CURLOPT_POSTFIELDS => $content,
                CURLOPT_USERPWD => "$username:$password"
            ]
        );

        // NOTE: Sometimes the response can give an "There was a problem with authentication" error.
        if($response === "There was a problem with authentication") {
            throw new SurrealException($response);
        }

        return $response->response;
    }

    /**
     * @throws Exception
     */
    public function export(string $username, string $password): string
    {
        return $this->execute(
            endpoint: "/export",
            method: HTTPMethod::GET,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_ACCEPT,
                    "Surreal-NS: " . $this->getNamespace(),
                    "Surreal-DB: " . $this->getDatabase()
                ],
                CURLOPT_USERPWD => "$username:$password"
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function signin(array $data): ?string
    {
        /** @var AuthResponse $response */
        $response = $this->execute(
            endpoint: "/signin",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_ACCEPT,
                    HTTP_CONTENT_TYPE
                ],
                CURLOPT_POSTFIELDS => json_encode($data)
            ]
        );

        return $response->token;
    }

    /**
     * @throws Exception
     */
    public function signup(array $data): ?string
    {
        /** @var AuthResponse $response */
        $response = $this->execute(
            endpoint: "/signup",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_ACCEPT,
                    HTTP_CONTENT_TYPE
                ],
                CURLOPT_POSTFIELDS => json_encode($data)
            ]
        );

        return $response->token;
    }

    /**
     * @param string $table
     * @param mixed $data
     * @return object|null
     * @throws Exception
     */
    public function create(string $table, mixed $data): ?array
    {
        $header = [
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...parent::getAuthHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => RpcMessage::create("create")
                    ->setParams([$table, $data])
                    ->toCborString()
            ]
        );

        return $response->response[0]["result"][0];
    }

    /**
     * @param string $thing
     * @param mixed $data
     * @return object|null
     * @throws Exception
     */
    public function update(string $thing, mixed $data): ?array
    {
        $header = [
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...parent::getAuthHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::PUT,
            options: [
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => RpcMessage::create("update")
                    ->setParams([$thing, $data])
                    ->toCborString()
            ]
        );

        return $response->response[0]["result"][0];
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, mixed $data): ?array
    {
        $header = [
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...parent::getAuthHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::PATCH,
            options: [
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => RpcMessage::create("merge")
                    ->setParams([$thing, $data])
                    ->toCborString()
            ]
        );

        return $response->response[0]["result"][0];
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): ?array
    {
        $header = [
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...parent::getAuthHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => RpcMessage::create("delete")
                    ->setParams([$thing])
                    ->toCborString()
            ]
        );

        return $response->response[0]["result"][0];
    }

    /**
     * Execute a SQL query.
     * @param string $query
     * @param array $params
     * @return array|null
     * @throws Exception
     */
    public function query(string $query, array $params = []): ?array
    {
        $header = [
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...parent::getAuthHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/rpc",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => RpcMessage::create("query")
                    ->setParams([$query, $params])
                    ->toCborString()
            ]
        );

        return $response->response;
    }

    public function close(): void
    {
        if ($this->client === null) {
            throw new RuntimeException("The database connection is already closed.");
        }

        $this->auth->setToken(null);

        curl_close($this->client);
        $this->client = null;
    }

    /**
     * @throws RuntimeException
     */
    private function baseExecute(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): void
    {
        if ($this->client === null) {
            throw new RuntimeException("The curl client is not initialized.");
        }

        curl_setopt($this->client, CURLOPT_URL, $this->host . $endpoint);
        curl_setopt($this->client, CURLOPT_CUSTOMREQUEST, $method->value);

        curl_setopt_array($this->client, $options);

        // throwing an exception if the request fails.
        if (curl_exec($this->client) === false) {
            throw new RuntimeException(curl_error($this->client));
        }
    }

    /**
     * @param string $endpoint
     * @param HTTPMethod $method
     * @param array $options
     * @return ResponseInterface|int|string|array
     * @throws Exception|RuntimeException
     */
    private function execute(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): ResponseInterface|int|string|array
    {
        $this->baseExecute($endpoint, $method, $options);

        // get the content type of the response.
        $content_type = curl_getinfo($this->client, CURLINFO_CONTENT_TYPE);
        $content_body = curl_multi_getcontent($this->client);

        $result = match ($content_type) {
            "application/json" => json_decode($content_body, true),
            "application/surrealdb", "application/cbor" => CBOR::decode($content_body),
            false, "text/plain; charset=utf-8" => $content_body,
            default => throw new Exception("Unsupported content type: $content_type"),
        };

        return match ($content_type) {
            "application/surrealdb", "application/json", "application/cbor" => ResponseParser::create($result),
            false, "text/plain; charset=utf-8" => $result,
        };
    }

    /**
     * Executes a request without expecting a response.
     * uses the health, status endpoints.
     */
    private function checkStatusCode(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): int
    {
        $this->baseExecute($endpoint, $method, $options);
        return curl_getinfo($this->client, CURLINFO_RESPONSE_CODE);
    }
}
