<?php

namespace Surreal\abstracts;

use Surreal\SurrealAuthorization;

abstract class SurrealBase
{
    /**
     * @var string
     */
    protected string $host;

    /**
     * @var string|null
     */
    protected ?string $namespace = null;

    /**
     * @var string|null
     */
    protected ?string $database = null;

    /**
     * @var SurrealAuthorization
     */
    protected SurrealAuthorization $authorization;

    public function __construct()
    {
        $this->authorization = new SurrealAuthorization();
    }

    /**
     * @param string|null $namespace
     * @param string|null $database
     * @return void
     */
    public function use(?string $namespace = null, ?string $database = null): void
    {
        if ($namespace) {
            $this->namespace = $namespace;
        }

        if ($database) {
            $this->database = $database;
        }
    }

    /**
     * @param string|null $namespace
     * @return SurrealBase
     */
    public function setNamespace(?string $namespace): SurrealBase
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @param string|null $database
     * @return SurrealBase
     */
    public function setDatabase(?string $database): SurrealBase
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * @param string|null $scope
     * @return SurrealBase
     */
    public function setScope(?string $scope): SurrealBase
    {
        $this->authorization->setScope($scope);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScope(): ?string
    {
        return $this->authorization->getScope();
    }

    /**
     * @param string|null $namespace
     * @return SurrealBase
     */
    public function setAuthNamespace(?string $namespace): SurrealBase
    {
        $this->authorization->setAuthNamespace($namespace);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthNamespace(): ?string
    {
        return $this->authorization->getAuthNamespace();
    }

    /**
     * @param string|null $database
     * @return SurrealBase
     */
    public function setAuthDatabase(?string $database): SurrealBase
    {
        $this->authorization->setAuthDatabase($database);
        return clone $this;
    }

    /**
     * @return string|null
     */
    public function getAuthDatabase(): ?string
    {
        return $this->authorization->getAuthDatabase();
    }

    /**
     * Set the authorization token for the current connection.
     * @param string|null $token
     * @return $this
     */
    public function setAuthToken(?string $token): SurrealBase
    {
        $this->authorization->setAuthToken($token);
        return $this;
    }

    /**
     * Constructs the base http headers for the request.
     * @param array $header
     * @return array
     */
    protected function constructHeader(array $header = []): array
    {
        if ($this->namespace) {
            $header[] = "Surreal-NS: " . $this->namespace;
        }

        if ($this->database) {
            $header[] = "Surreal-DB: " . $this->database;
        }

        if ($this->authorization) {
            $token = $this->authorization->getAuthToken();
            if ($token) {
                $header[] = "Authorization: Bearer " . $token;
            }
        }

        return $header;
    }
}