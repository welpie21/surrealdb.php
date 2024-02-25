<?php

namespace Surreal\abstracts;

use Closure;
use Surreal\interfaces\SurrealApi;

abstract class AbstractProtocol extends AbstractSurreal implements SurrealApi
{
    /**
     * @param string $host
     * @param array{namespace:string|null,database:string|null,scope:string|null} $target
     * @param AbstractAuth|null $authorization
     */
    public function __construct(string $host, array $target = [], ?AbstractAuth $authorization = null)
    {
        // assign base properties.
        $this->host = $host;
        $this->use($target);

        parent::__construct($host, $target, $authorization);
    }

    /**
     * Set a timeout for the request in seconds
     * @param int $seconds
     * @return Closure
     */
    abstract public function setTimeout(int $seconds): Closure;
}