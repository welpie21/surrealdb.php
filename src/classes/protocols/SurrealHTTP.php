<?php

namespace Surreal\classes\protocols;

use Closure;
use CurlHandle;
use Exception;
use Surreal\abstracts\AbstractProtocol;
use Surreal\classes\CBOR;
use Surreal\classes\exceptions\SurrealException;
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
     */
    public function status(): int
    {
        $client = $this->execute(
            endpoint: "/status",
            method: HTTPMethod::GET
        );

        return $this->getCurlResponseCode($client);
    }

    /**
     * @throws SurrealException
     */
    public function health(): int
    {
        $client = $this->execute(
            endpoint: "/health",
            method: HTTPMethod::GET
        );

        return $this->getCurlResponseCode($client);
    }

    /**
     * @throws Exception
     */
    public function version(): ?string
    {
        $client = $this->execute(
            endpoint: "/version",
            method: HTTPMethod::GET
        );

        return $this->getCurlResponse($client);
    }

    /**
     * @throws Exception
     */
    public function import(string $content, string $username, string $password): string
    {
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
        curl_close($this->client);
        $this->client = null;
    }

    /**
     * @param string $endpoint
     * @param HTTPMethod $method
     * @param array $options
     * @return CurlHandle
     * @throws SurrealException
     */
    private function execute(
        string     $endpoint,
        HTTPMethod $method,
        array      $options = []
    ): CurlHandle
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

        return $this->client;
    }
}
