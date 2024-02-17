<?php

namespace Surreal;

use CurlHandle;
use Exception;
use Surreal\abstracts\SurrealBase;
use Surreal\classes\CBORHandler;
use Surreal\classes\SurrealAuthResponse;
use Surreal\classes\SurrealResponse;
use Surreal\enums\HTTPMethod;
use Surreal\interfaces\SurrealAPI;

const HTTP_ACCEPT = "Accept: application/cbor";
const HTTP_CONTENT_TYPE = "Content-Type: application/cbor";

class Surreal extends SurrealBase implements SurrealAPI
{
    private ?CurlHandle $client;

    public function __construct(string $host, ?string $namespace = null, ?string $database = null)
    {
        // assign base properties.
        $this->host = $host;
        $this->use($namespace, $database);

        // initialize the curl client.
        $this->client = curl_init();

        curl_setopt($this->client, CURLOPT_RETURNTRANSFER, 1);

        parent::__construct();
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

    public function version(): string|null
    {
        $this->execute("/version", HTTPMethod::GET);
        return $this->getResponseContent();
    }

    public function import(string $content): string
    {
        $this->execute(
            endpoint: "/import",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => $this->constructHeader()
            ]
        );

        return $this->getResponseContent();
    }

    public function export(): string
    {
        $this->execute(
            endpoint: "/export",
            method: HTTPMethod::GET,
            options: [
                CURLOPT_HTTPHEADER => $this->constructHeader()
            ]
        );

        return $this->getResponseContent();
    }

    /**
     * @throws Exception
     */
    public function signin(mixed $data): SurrealAuthResponse
    {
        $header = $this->authorization->constructAuthHeader([]);

        $this->execute(
            endpoint: "/signin",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_POSTFIELDS => json_encode($data)
            ]
        );

        $result = $this->getResponseContent();
//        $result = CBORHandler::decode($result);
        var_dump($result);

//        $this->setAuthToken($response->token);



        return new SurrealAuthResponse($result[0]);
    }

    /**
     * @throws Exception
     */
    public function signup(mixed $data): mixed
    {
        $this->execute(
            endpoint: "/signup",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_HTTPHEADER => [
                    "Surreal-Auth-NS: " . $this->namespace,
                    "Surreal-Auth-DB: " . $this->database,
                ]
            ]
        );

        return CBORHandler::decode($this->getResponseContent());
    }

    public function invalidate(): void
    {
        $this->authorization->invalidate();
    }

    /**
     * @throws Exception
     */
    public function create(string $table, mixed $data): SurrealResponse
    {
        $header = $this->constructHeader([
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$table",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        $result = $this->getResponseContent();
        $result = CBORHandler::decode($result);

        return new SurrealResponse($result[0]);
    }

    /**
     * @throws Exception
     */
    public function update(string $thing, mixed $data): SurrealResponse
    {
        $headers = $this->constructHeader([
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::PUT,
            options: [
                CURLOPT_POSTFIELDS => CBORHandler::encode($data),
                CURLOPT_HTTPHEADER => $headers
            ]
        );

        $result = $this->getResponseContent();
        $result = CBORHandler::decode($result);

        return new SurrealResponse($result[0]);
    }

    /**
     * @throws Exception
     */
    public function merge(string $thing, mixed $data): SurrealResponse
    {
        $header = $this->constructHeader([
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::PATCH,
            options: [
                CURLOPT_POSTFIELDS => CBORHandler::encode($data),
                CURLOPT_HTTPHEADER => $header
            ]
        );

        $result = $this->getResponseContent();
        $result = CBORHandler::decode($result);

        return new SurrealResponse($result[0]);
    }

    /**
     * @throws Exception
     */
    public function delete(string $thing): SurrealResponse
    {
        $header = $this->constructHeader([
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE
        ]);

        $this->execute(
            endpoint: "/key/$thing",
            method: HTTPMethod::DELETE,
            options: [
                CURLOPT_HTTPHEADER => $header
            ]
        );

        $result = $this->getResponseContent();
        $result = CBORHandler::decode($result);

        return new SurrealResponse($result[0]);
    }

    /**
     * @throws Exception
     */
    public function sql(string $query): SurrealResponse
    {
        $header = $this->constructHeader([
            HTTP_ACCEPT,
            HTTP_CONTENT_TYPE
        ]);

        var_dump($header);

        $this->execute(
            endpoint: "/sql",
            method: HTTPMethod::POST,
            options: [
                CURLOPT_POSTFIELDS => $query,
                CURLOPT_HTTPHEADER => $header
            ]
        );

        $result = $this->getResponseContent();
        $result = CBORHandler::decode($result);

        return new SurrealResponse($result[0]);
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

        curl_setopt_array($this->client, $options);

        curl_exec($this->client);
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
     */
    private function getResponseContent(): string|null
    {
        return curl_multi_getcontent($this->client);
    }
}