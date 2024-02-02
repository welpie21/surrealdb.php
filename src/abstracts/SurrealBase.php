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
     * @var SurrealAuthorization|null
     */
    protected ?SurrealAuthorization $authorization = null;

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
     * Set the namespace for the current connection.
     * @param string|null $namespace
     * @return SurrealBase
     */
    public function setNamespace(?string $namespace): SurrealBase
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Set the database pointer for the current connection.
     * @param string|null $database
     * @return SurrealBase
     */
    public function setDatabase(?string $database): SurrealBase
    {
        $this->database = $database;
        return $this;
    }

    /**
     * Set the scope for the current connection.
     * @param string|null $scope
     * @return SurrealBase
     */
    public function setScope(?string $scope): SurrealBase
    {
        $this->authorization->setScope($scope);
        return $this;
    }

    /**
     * Constructs the base http headers for the request.
     * @return array
     */
    protected function constructBaseHTTPHeader(): array
    {
        $header = [];

        if ($this->namespace) {
            $header["Surreal-NS"] = $this->namespace;
        }

        if ($this->database) {
            $header["Surreal-DB"] = $this->database;
        }

        if ($this->authorization) {
            $token = $this->authorization->getAuthToken();
            if ($token) {
                $header["Surreal-Auth"] = $token;
            }
        }

        return $header;
    }
}