<?php

namespace Surreal;

use CurlHandle;
use Exception;
use Surreal\abstracts\SurrealBase;
use Surreal\classes\CBORHandler;
use Surreal\classes\response\SurrealAuthResponse;
use Surreal\classes\response\SurrealErrorResponse;
use Surreal\classes\response\SurrealResponse;
use Surreal\enums\HTTPMethod;
use Surreal\interfaces\SurrealAPI;

const HTTP_CBOR_ACCEPT = "Accept: application/cbor";
const HTTP_CBOR_CONTENT_TYPE = "Content-Type: application/cbor";
const HTTP_JSON_ACCEPT = "Accept: application/json";
const HTTP_JSON_CONTENT_TYPE = "Content-Type: application/json";

class Surreal extends SurrealBase implements SurrealAPI
{
    private ?CurlHandle $client;

    public function __construct(
        string                $host,
        ?string               $namespace = null,
        ?string               $database = null,
        ?SurrealAuthorization $authorization = null
    )
    {
        // assign base properties.
        $this->host = $host;
        $this->use($namespace, $database);

        // initialize the curl client.
        $this->client = curl_init();

        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, 1);

        parent::__construct($authorization);
    }

    /**
     * Set the timeout for the curl client. default is 10 seconds.
     * @param int $timeout
     * @return void
     */
    public function setTimeout(int $timeout): void
    {
        curl_setopt($this->client, CURLOPT_TIMEOUT, $timeout);
    }

    public function status(): int
    {
        $this->execute("/status", HTTPMethod::GET);
        return $this->getResponseCode();
    }

    public function health(): int
    {
        $this->execute("/health", HTTPMethod::GET);
        return $this->getResponseCode();
    }

    /**
     * @throws Exception
     */
    public function version(): string|null
    {
        $this->execute("/version", HTTPMethod::GET);
        return $this->getResponseContent();
    }

    /**
     * @throws Exception
     */
    public function import(string $content, string $username, string $password): string
    {
        $header = $this->constructHeader();

        $this->execute(
            endpoint: "/import",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => array_merge($header, [
                    HTTP_JSON_ACCEPT,
                    "Content-Type: text/plain"
                ]),
                CURLOPT_POSTFIELDS => $content,
                CURLOPT_USERPWD => "$username:$password"
            ]
        );

        return $this->getResponseContent();
    }

    /**
     * @throws Exception
     */
    public function export(string $username, string $password): string
    {
        $header = $this->constructHeader();

        $this->execute(
            endpoint: "/export",
            method: HTTPMethod::GET,
            options: [
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_USERPWD => "$username:$password"
            ]
        );

        return $this->getResponseContent();
    }

    /**
     * @throws Exception
     */
    public function signin(mixed $data): string
    {
        $data = $this->parseAuthTarget($data);

        $this->execute(
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

        return $this->parseResponse();
    }

    /**
     * @throws Exception
     */
    public function signup(mixed $data): string|array|null|object
    {
        $data = $this->parseAuthTarget($data);

        $this->execute(
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

        return $this->parseResponse();
    }

    /**
     * Invalidate the current token.
     * @return void
     */
    public function invalidate(): void
    {
        $this->authorization->invalidate();
    }

    /**
     * @param string $table
     * @param mixed $data
     * @return object|null
     * @throws Exception
     */
    public function create(string $table, mixed $data): object|null
    {
        $header = $this->constructHeader([
            HTTP_CBOR_ACCEPT,
            HTTP_CBOR_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$table",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$this->parseResponse()[0];
    }

    /**
     * @param string $thing
     * @param mixed $data
     * @return object|null
     * @throws Exception
     */
    public function update(string $thing, mixed $data): object|null
    {
        $headers = $this->constructHeader([
            HTTP_CBOR_ACCEPT,
            HTTP_CBOR_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::PUT,
            options: [
                CURLOPT_POSTFIELDS => CBORHandler::encode($data),
                CURLOPT_HTTPHEADER => $headers
            ]
        );

        return (object)$this->parseResponse()[0];
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, mixed $data): object|null
    {
        $header = $this->constructHeader([
            HTTP_CBOR_ACCEPT,
            HTTP_CBOR_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::PATCH,
            options: [
                CURLOPT_POSTFIELDS => CBORHandler::encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$this->parseResponse()[0];
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): object|null
    {
        $header = $this->constructHeader([
            HTTP_CBOR_ACCEPT,
            HTTP_CBOR_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::DELETE,
            options: [
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$this->parseResponse()[0];
    }

    /**
     * Execute a SQL query.
     * @param string $query
     * @return array|object|null
     * @throws Exception
     */
    public function sql(string $query): array|object|null
    {
        $header = $this->constructHeader([
            HTTP_CBOR_ACCEPT,
            HTTP_CBOR_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/sql",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_POSTFIELDS => $query,
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return $this->parseResponse();
    }

    public function close(): void
    {
        curl_close($this->client);
        $this->client = null;
    }

    /**
     * @param string $endpoint
     * @param HTTPMethod $method
     * @param array $options
     * @return void
     */
    private function execute(
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
        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt_array($this->client, $options);

        curl_exec($this->client);
    }

    /**
     * Retrieves the response type that has been sent from the server.
     * @return string|null
     */
    private function getResponseType(): string|null
    {
        return curl_getinfo($this->client, CURLINFO_CONTENT_TYPE);
    }

    /**
     * @throws Exception
     */
    private function parseResponse(): array|object|string|null
    {
        $response = $this->parseResponseContent();

        if (isset($response["token"])) {
            $authorization = new SurrealAuthResponse($response);
            return $authorization->token;
        }

        $response = new SurrealResponse($response);
        return $response->result;
    }

    /**
     * @return object|null
     * @throws Exception
     */
    private function parseResponseContent(): array|null
    {
        $content = $this->getResponseContent();

        if ($content === null) {
            return null;
        }

        $response = match ($this->getResponseType()) {
            "application/cbor" => CBORHandler::decode($content)[0],
            "application/json" => (array)json_decode($content),
            default => null
        };

        // check if the response is an error response.
        if (isset($response["information"])) {
            throw new SurrealErrorResponse($response);
        }

        return $response;
    }

    /**
     * Returns the response code from the curl client.
     * @return int
     */
    private function getResponseCode(): int
    {
        return curl_getinfo($this->client, CURLINFO_HTTP_CODE);
    }

    /**
     * Returns the response content from the curl client.
     * @return string|null
     * @throws Exception
     */
    private function getResponseContent(): string|null
    {
        return curl_multi_getcontent($this->client);
    }

    /**
     * Parses the authentication target what will be used for the payload for signin and signup.
     * @param array $data
     * @return array
     */
    private function parseAuthTarget(array $data): array
    {
        if ($namespace = $this->getAuthNamespace()) {
            $data["ns"] = $namespace;
        } else if ($namespace = $this->getNamespace()) {
            $data["ns"] = $namespace;
        }

        if ($database = $this->getAuthDatabase()) {
            $data["db"] = $database;
        } else if ($database = $this->getDatabase()) {
            $data["db"] = $database;
        }

        if ($scope = $this->getScope()) {
            $data["sc"] = $scope;
        }

        return $data;
    }
}