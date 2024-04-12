<?php

namespace Surreal\abstracts;

use Surreal\Auth\SurrealAuth;
use Surreal\Client;

abstract class AbstractSurreal
{
    protected string $host;
    protected ?string $namespace = null;
    protected ?string $database = null;
    protected SurrealAuth $auth;

    /**
     * @param string $host
     * @param array{namespace:string,database:string|null} $target
     * @param SurrealAuth|null $authorization
     */
    public function __construct(
        string        $host,
        array         $target = [],
        ?SurrealAuth $authorization = null
    )
    {
        $this->host = $host;
        $this->auth = $authorization ?? new SurrealAuth();

        $this->use($target);
    }

    /**
     * @param array{namespace:string|null,database:string|null} $target
     * @return null
     */
    public function use(array $target): null
    {
        if ($namespace = $target["namespace"]) {
            $this->setNamespace($namespace);
        }

        if ($database = $target["database"]) {
            $this->setDatabase($database);
        }

        return null;
    }

    /**
     * Set the current namespace
     * @param string|null $namespace
     * @return void
     */
    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * Set the current database
     * @param string|null $database
     * @return void
     */
    public function setDatabase(?string $database): void
    {
        $this->database = $database;
    }

    /**
     * Returns the current set namespace
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * Returns the current set database
     * @return string|null
     */
    public function getDatabase(): ?string
    {
        return $this->database;
    }

    /**
     * Returns the current set host
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }
}