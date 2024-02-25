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

        // set the namespace, database, and scope
        parent::setTarget($target);
    }

    /**
     * @param array{namespace:string|null,database:string|null} $target
     * @return void
     */
    public function use(array $target): void
    {
        if ($namespace = $target["namespace"]) {
            $this->namespace = $namespace;
        }

        if ($database = $target["database"]) {
            $this->database = $database;
        }
    }
}