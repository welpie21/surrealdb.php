<?php

namespace Surreal\abstracts;

use Surreal\classes\auth\SurrealAuth;

abstract class AbstractSurreal
{
    protected string $host;
    protected ?string $namespace = null;
    protected ?string $database = null;
    protected AbstractAuth $auth;

    /**
     * @param string $host
     * @param array{namespace:string,database:string|null} $target
     * @param AbstractAuth|null $authorization
     */
    public function __construct(
        string        $host,
        array         $target = [],
        ?AbstractAuth $authorization = null
    )
    {
        $this->auth = $authorization ?? new SurrealAuth();
        $this->use($target);
    }

    /**
     * @param array{namespace:string|null,database:string|null} $target
     * @return void
     */
    public function use(array $target): void
    {
        if ($namespace = $target["namespace"]) {
            $this->setNamespace($namespace);
        }

        if ($database = $target["database"]) {
            $this->setDatabase($database);
        }
    }

    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function setDatabase(?string $database): void
    {
        $this->database = $database;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getDatabase(): ?string
    {
        return $this->database;
    }
}