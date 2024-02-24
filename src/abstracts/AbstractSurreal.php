<?php

namespace Surreal\abstracts;

use JetBrains\PhpStorm\ArrayShape;
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

    public function __construct(?AbstractAuth $authorization = null)
    {
        $this->auth = $authorization ?? new SurrealAuth();
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

    /**
     * @param array $headers
     * @param array $omit
     * @return array
     */
    protected function parseHeaders(
        array $headers,
        #[ArrayShape(["namespace" => "string", "database" => "string", "scope" => "string"])]
        array $omit = []
    ): array
    {
        $result = AbstractTarget::parse($this, $this->auth);
        $result = array_merge($result, $headers);

        return array_diff_key($result, array_flip($omit));
    }
}