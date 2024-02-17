<?php

namespace Surreal;

class SurrealAuthorization
{
    /**
     * Holds the authorization token for the current connection.
     * @var string|null
     */
    private ?string $token = null;

    /**
     * @var string|null
     */
    private ?string $namespace = null;

    /**
     * @var string|null
     */
    private ?string $database = null;

    /**
     * @var string|null
     */
    private ?string $scope = null;

    /**
     * @param string $token
     * @return string
     */
    public function setAuthToken(string $token): string
    {
        return $this->token = "Bearer $token";
    }

    /**
     * @return string|null
     */
    public function getAuthToken(): string|null
    {
        return $this->token;
    }

    /**
     * @param string $scope
     * @return void
     */
    public function setScope(string $scope): void
    {
        $this->scope = $scope;
    }

    /**
     * @return string|null
     */
    public function getScope(): string|null
    {
        return $this->scope;
    }

    /**
     * @param string $namespace
     * @return void
     */
    public function setAuthNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string|null
     */
    public function getAuthNamespace(): string|null
    {
        return $this->namespace;
    }

    /**
     * @param string $database
     * @return void
     */
    public function setAuthDatabase(string $database): void
    {
        $this->database = $database;
    }

    /**
     * @return string|null
     */
    public function getAuthDatabase(): string|null
    {
        return $this->database;
    }

    /**
     * Invalidates the authorization token for the current connection.
     * @return void
     */
    public function invalidate(): void
    {
        $this->token = null;
    }

    /**
     * @param array $array
     * @param bool $includeToken
     * @return array
     */
    public function constructAuthHeader(array $array, bool $includeToken = false): array
    {
        if ($this->token && $includeToken) {
            $array[] = "Authorization: " . $this->getAuthToken();
        }

        if ($this->namespace) {
            $array[] = "Surreal-Auth-NS: " . $this->getAuthNamespace();
        }

        if ($this->database) {
            $array[] = "Surreal-Auth-DB: " . $this->getAuthDatabase();
        }

        if ($this->scope) {
            $array[] = "Surreal-Auth-SC: " . $this->getScope();
        }

        return $array;
    }
}