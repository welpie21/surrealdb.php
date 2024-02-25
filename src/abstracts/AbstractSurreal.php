<?php

namespace Surreal\abstracts;

use Surreal\classes\auth\SurrealAuth;

abstract class AbstractSurreal extends AbstractTarget
{
    /**
     * @var string
     */
    protected string $host;

    /**
     * @var AbstractAuth
     */
    protected AbstractAuth $auth;

    /**
     * @param string $host
     * @param array{namespace:string,database:string|null} $target
     * @param AbstractAuth|null $authorization
     */
    public function __construct(
        string $host,
        array $target = [],
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
            parent::setNamespace($namespace);
        }

        if ($database = $target["database"]) {
            parent::setDatabase($database);
        }
    }
}