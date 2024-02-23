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
     * @param string|null $token
     * @return void
     */
    public function setAuthToken(?string $token): void
    {
        if ($token === null) {
            return;
        }

        $this->token = $token;
    }

    /**
     * @return string|null
     */
    public function getAuthToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $scope
     * @return SurrealAuthorization
     */
    public function setScope(string $scope): SurrealAuthorization
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->scope;
    }

    /**
     * @param string $namespace
     * @return SurrealAuthorization
     */
    public function setAuthNamespace(string $namespace): SurrealAuthorization
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string $database
     * @return SurrealAuthorization
     */
    public function setAuthDatabase(string $database): SurrealAuthorization
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthDatabase(): ?string
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

    public static function create(): SurrealAuthorization
    {
        return new SurrealAuthorization();
    }
}