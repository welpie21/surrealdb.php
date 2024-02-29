<?php

namespace Surreal;

use Closure;
use CurlHandle;
use Exception;
use Surreal\abstracts\AbstractProtocol;
use Surreal\abstracts\AbstractResponse;
use Surreal\classes\CBOR;
use Surreal\classes\exceptions\SurrealException;
use Surreal\classes\ResponseParser;
use Surreal\classes\responses\AnyResponse;
use Surreal\classes\responses\AuthResponse;
use Surreal\enums\HTTPMethod;
use Surreal\traits\HTTPTrait;
use Surreal\traits\SurrealTrait;

const HTTP_CBOR_ACCEPT = "Accept: application/cbor";
const HTTP_CBOR_CONTENT_TYPE = "Content-Type: application/cbor";
const HTTP_JSON_ACCEPT = "Accept: application/json";
const HTTP_JSON_CONTENT_TYPE = "Content-Type: application/json";

class SurrealHTTP extends AbstractProtocol
{
    use HTTPTrait, SurrealTrait;

    private ?CurlHandle $client;

    /**
     * @param string $host
     * @param array{namespace:string, database:string|null} $target
     */
    public function __construct(
        string $host,
        array  $target = []
    )
    {
        // initialize the curl client.
        $this->client = curl_init();

        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->client, CURLOPT_TIMEOUT, 5);

        parent::__construct($host, $target);
    }

    /**
     * Set the timeout for the curl client. default is 5 seconds.
     * @param int $seconds
     * @return Closure - Reset the timeout to previous set timeout value.
     */
    public function setTimeout(int $seconds): Closure
    {
        $reset = function (): void {
            $timeout = curl_getinfo($this->client, CURLOPT_TIMEOUT);
            curl_setopt($this->client, CURLOPT_TIMEOUT, $timeout);
        };

        curl_setopt($this->client, CURLOPT_TIMEOUT, $seconds);

        return $reset;
    }

    /**
     * @throws SurrealException
     */
    public function status(): int
    {
        return $this->checkStatusCode(
            endpoint: "/status",
            method: HTTPMethod::GET
        );
    }

    /**
     * @throws SurrealException
     */
    public function health(): int
    {
        return $this->checkStatusCode(
            endpoint: "/health",
            method: HTTPMethod::GET
        );
    }

    /**
     * @throws SurrealException
     */
    public function version(): ?string
    {
        return $this->execute(
            endpoint: "/version",
            method: HTTPMethod::GET
        );
    }

    /**
     * @return array - Array of SingleRecordResponse
     * @throws Exception
     */
    public function import(string $content, string $username, string $password): array
    {
        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/import",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_JSON_ACCEPT,
                    "Content-Type: text/plain",
                    "Surreal-NS: " . $this->getNamespace(),
                    "Surreal-DB: " . $this->getDatabase()
                ],
                CURLOPT_POSTFIELDS => $content,
                CURLOPT_USERPWD => "$username:$password"
            ]
        );

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
                    HTTP_JSON_ACCEPT,
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
    public function signin(mixed $data): ?string
    {
        /** @var AuthResponse $response */
        $response = $this->execute(
            endpoint: "/signin",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_JSON_ACCEPT,
                    HTTP_JSON_CONTENT_TYPE
                ],
                CURLOPT_POSTFIELDS => json_encode($data)
            ]
        );

        return $response->token;
    }

    /**
     * @throws Exception
     */
    public function signup(mixed $data): ?string
    {
        /** @var AuthResponse $response */
        $response = $this->execute(
            endpoint: "/signup",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => [
                    HTTP_JSON_ACCEPT,
                    HTTP_JSON_CONTENT_TYPE
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
    public function create(string $table, mixed $data): ?object
    {
        $header = [
            HTTP_JSON_ACCEPT,
            HTTP_JSON_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...$this->auth->getHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/key/$table",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$response->response[0]["result"][0];
    }

    /**
     * @param string $thing
     * @param mixed $data
     * @return object|null
     * @throws Exception
     */
    public function update(string $thing, mixed $data): ?object
    {
        [$table, $id] = $this->parseThing($thing);

        $header = [
            HTTP_JSON_ACCEPT,
            HTTP_JSON_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...$this->auth->getHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/key/$table/$id",
            method: HTTPMethod::PUT,
            options: [
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$response->response[0]["result"][0];
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, mixed $data): ?object
    {
        [$table, $id] = $this->parseThing($thing);

        $header = [
            HTTP_JSON_ACCEPT,
            HTTP_JSON_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...$this->auth->getHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/key/$table/$id",
            method: HTTPMethod::PATCH,
            options: [
                CURLOPT_POSTFIELDS => CBOR::encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$response->response[0]["result"][0];
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): ?object
    {
        [$table, $id] = $this->parseThing($thing);

        $header = [
            HTTP_JSON_ACCEPT,
            HTTP_JSON_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...$this->auth->getHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/key/$table/$id",
            method: HTTPMethod::DELETE,
            options: [
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$response->response[0]["result"][0];
    }

    /**
     * Execute a SQL query.
     * @param string $query
     * @return array|object|null
     * @throws Exception
     */
    public function sql(string $query): array|object|null
    {
        $header = [
            HTTP_JSON_ACCEPT,
            HTTP_JSON_CONTENT_TYPE,
            "Surreal-NS: " . $this->getNamespace(),
            "Surreal-DB: " . $this->getDatabase(),
            ...$this->auth->getHeaders()
        ];

        /** @var AnyResponse $response */
        $response = $this->execute(
            endpoint: "/sql",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_POSTFIELDS => $query,
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return $response->response;
    }

    /**
     * Invalidate the current token.
     * @return void
     */
    public function invalidate(): void
    {
        $this->auth->setToken(null);
    }

    public function setToken(?string $token): void
    {
        $this->auth->setToken($token);
    }

    public function getToken(): ?string
    {
        return $this->auth->getToken();
    }

    public function close(): void
    {
        if ($this->client === null) {
            throw new \RuntimeException("The database connection is already closed.");
        }

        curl_close($this->client);
        $this->client = null;
    }

    /**
     * @throws SurrealException
     */
    private function baseExecute(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): void
    {
        if ($this->client === null) {
            throw new \RuntimeException("The curl client is not initialized.");
        }

        curl_setopt($this->client, CURLOPT_URL, $this->host . $endpoint);
        curl_setopt($this->client, CURLOPT_CUSTOMREQUEST, $method->value);

        curl_setopt_array($this->client, $options);

        // throwing an exception if the request fails.
        if (curl_exec($this->client) === false) {
            throw new SurrealException(curl_error($this->client));
        }
    }

    /**
     * @param string $endpoint
     * @param HTTPMethod $method
     * @param array $options
     * @return AbstractResponse|int|string|array
     * @throws SurrealException|Exception
     */
    private function execute(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): AbstractResponse|int|string|array
    {
        $this->baseExecute($endpoint, $method, $options);

        // get the content type of the response.
        $content_type = curl_getinfo($this->client, CURLINFO_CONTENT_TYPE);
        $content_body = curl_multi_getcontent($this->client);

        $result = match ($content_type) {
            "application/json" => json_decode($content_body, true),
            "application/cbor" => CBOR::decode($content_body),
            false, "text/plain; charset=utf-8" => $content_body,
            default => throw new SurrealException("Unsupported content type: $content_type"),
        };

        if ($content_type === "application/json" || $content_type === "application/cbor") {
            $parser = new ResponseParser($result);
            return $parser->getResponse();
        }

        return $result;
    }

    /**
     * Executes a request without expecting a response.
     * uses the health, status endpoints.
     * @throws SurrealException
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
