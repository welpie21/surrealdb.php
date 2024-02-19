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
     * @var array|null
     */
    private ?array $extras = null;

    /**
     * @param string $token
     * @return string
     */
    public function setAuthToken(string $token): string
    {
        return $this->token = $token;
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
    public function getScope(): string|null
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
    public function getAuthNamespace(): string|null
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
    public function getAuthDatabase(): string|null
    {
        return $this->database;
    }

    /**
     * Set additional extras for the current connection.
     * @param array $extras
     * @return $this
     */
    public function setExtras(array $extras): SurrealAuthorization
    {
        $this->extras = $extras;
        return $this;
    }

    /**
     * Get the additional extras for the current connection.
     * @return array|null
     */
    public function getExtras(): array|null
    {
        return $this->extras;
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