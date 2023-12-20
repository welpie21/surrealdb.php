<?php

namespace Surreal\abstracts;

use CurlHandle;
use Surreal\enums;

abstract readonly class SurrealConnector
{
    protected string $namespace;
    protected string $database;
    protected string $username;
    protected string $password;
    protected enums\auth $auth;

    public function __construct(
        protected string $host,
        protected int $port,
        protected enums\connector $protocol
    ) { }

    /**
     * returns the base url where you can apply a prefix.
     * @param enums\connector $prefix
     * @return string
     */
    private function base(enums\connector $prefix): string {
        $protocol = $prefix->value;
        return "$protocol://$this->host:$this->port";
    }

    /**
     * returns the complete url
     * @return string
     */
    private function url(): string{
        return $this->base($this->protocol);
    }

    /**
     * returns the curl handler
     * @param string $path
     * @return CurlHandle | false
     */
    protected function getHandler(string $path): CurlHandle | false {
        $_path = $this->url() . "/" . $path;
        return curl_init($_path);
    }

    /**
     * Set the namespace you want to use.
     * @param string $namespace
     * @return $this
     */
    public function setNamespace(string $namespace): SurrealConnector {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * Set the database that you want to access.
     * @param string $database
     * return $this
     */
    public function setDatabase(string $database): SurrealConnector {
        $this->database = $database;
        return $this;
    }

    /**
     * Set the credentials
     * @param string $username
     * @param string $password
     * @return $this
     */
    public function setCredentials(string $username, string $password): SurrealConnector {
        $this->username = $username;
        $this->password = $password;

        return $this;
    }

    /**
     * Sets the authentication mode
     * @param enums\auth $mode
     * @return $this
     */
    public function setAuthMode(enums\auth $mode): SurrealConnector
    {
        $this->auth = $mode;
        return $this;
    }
}