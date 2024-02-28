<?php

namespace Surreal;

use Closure;
use CurlHandle;
use Exception;
use JsonException;
use Surreal\abstracts\AbstractProtocol;
use Surreal\abstracts\AbstractResponse;
use Surreal\classes\CBOR;
use Surreal\classes\exceptions\SurrealException;
use Surreal\classes\ResponseParser as ResponseParser;
use Surreal\classes\responses\AnyResponse;
use Surreal\enums\HTTPMethod;
use Surreal\traits\HTTPTrait;

const HTTP_CBOR_ACCEPT = "Accept: application/cbor";
const HTTP_CBOR_CONTENT_TYPE = "Content-Type: application/cbor";
const HTTP_JSON_ACCEPT = "Accept: application/json";
const HTTP_JSON_CONTENT_TYPE = "Content-Type: application/json";

class SurrealHTTP extends AbstractProtocol
{
    use HTTPTrait;

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
     * @throws JsonException
     */
    public function status(): int
    {
        return $this->execute(
            endpoint: "/status",
            method: HTTPMethod::GET
        );
    }

    /**
     * @throws SurrealException|JsonException
     */
    public function health(): int
    {
        return $this->execute(
            endpoint: "/health",
            method: HTTPMethod::GET
        );
    }

    /**
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
     * @return array - Array of QueryResponse
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
        $response = $this->execute(
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

        return $response;
    }

    /**
     * @throws Exception
     */
    public function signin(mixed $data): ?string
    {
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
    public function signup(mixed $data): ?string
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
    public function create(string $table, mixed $data): ?object
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
    public function update(string $thing, mixed $data): ?object
    {
        $headers = $this->constructHeader([
            HTTP_CBOR_ACCEPT,
            HTTP_CBOR_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::PUT,
            options: [
                CURLOPT_POSTFIELDS => CBOR::encode($data),
                CURLOPT_HTTPHEADER => $headers
            ]
        );

        return (object)$this->parseResponse()[0];
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, mixed $data): ?object
    {
        $header = $this->constructHeader([
            HTTP_CBOR_ACCEPT,
            HTTP_CBOR_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::PATCH,
            options: [
                CURLOPT_POSTFIELDS => CBOR::encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        return (object)$this->parseResponse()[0];
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): ?object
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
     * @param array $params
     * @return array|object|null
     * @throws Exception
     */
    public function sql(string $query, array $params): array|object|null
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
        if ($this->client === null) {
            throw new \RuntimeException("The database connection is already closed.");
        }

        curl_close($this->client);
        $this->client = null;
    }

    /**
     * @throws Exception
     */
    private function parseResponse(array $response): AbstractResponse
    {
        $response = new ResponseParser($response);
        return $response->getResponse();
    }

    /**
     * @param string $endpoint
     * @param HTTPMethod $method
     * @param array $options
     * @return AbstractResponse|int|string|array
     * @throws JsonException
     * @throws SurrealException
     * @throws Exception
     */
    private function execute(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): AbstractResponse|int|string|array
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

        // get the content type of the response.
        $content_type = curl_getinfo($this->client, CURLINFO_CONTENT_TYPE);
        $response = curl_multi_getcontent($this->client);

        // Here by we check if the content type is not json or cbor. If it's not, we throw an exception.
        // This is to ensure that we only receive json or cbor data. This code is done this way to reduce
        // the number of if statements in the code.
        if ($content_type === "text/plain; charset=utf-8") {
            return $response;
        }

        // Content type can return false if no content type is received.
        // In this case, we return the response code when it's false.
        if ($content_type === false) {

            if(is_string($response) && $response !== "")
                return $response;

            return curl_getinfo($this->client, CURLINFO_RESPONSE_CODE);
        }

        if ($content_type !== "application/json" && $content_type !== "application/cbor") {
            throw new SurrealException("The content type is not supported. " . $content_type);
        }

        $response = match ($content_type) {
            "application/json" => json_decode($response, true, 512, JSON_THROW_ON_ERROR),
            "application/cbor" => CBOR::decode($response),
        };

        return $this->parseResponse($response);
    }
}
