<?php

namespace Surreal\abstracts;

use Surreal\classes\SurrealAuthentication;

abstract class SurrealAPI
{
    public function __construct(
        public readonly string $namespace,
        public readonly string $database,
        public readonly string $host,
        public readonly SurrealAuthentication $authentication,
        public bool $ready = false
    ) { }

    /**
     * Establish a connection
     * @return void
     */
    abstract function connect(): void;

    /**
     * Returns the status of the connected SurrealDB
     * @return int
     */
    abstract function status(): int;

    /**
     * Returns the health of the connected SurrealDB
     * @return int
     */
    abstract function health(): int;

    /**
     * Outputs the version of the connected SurrealDB
     * @return string
     */
    abstract function version(): string;

    /**
     * Imports data into the pointed database
     * @return string
     */
    abstract function import(): string;

    /**
     * Exports the pointed database
     * @return string
     */
    abstract function export(): string;

    /**
     * Sign in an existing user
     * @return object
     */
    abstract function signin(): object;

    /**
     * Signs up a new user
     * @return object
     */
    abstract function signup(): object;

    /**
     * Invalidates the authentication for the current connection.
     */
    abstract function invalidate(): void;

    /**
     * Creates a record in the database.
     * @param string $thing
     * @param mixed|null $data
     * @return object|null
     */
    abstract function create(string $thing, mixed $data): object | null;

    /**
     * Updates all records in a table, or a specific record, in the database.
     * @param string $thing
     * @param mixed|null $data
     * @return object|null
     */
    abstract function update(string $thing, mixed $data): object | null;

    /**
     * Modifies all records in a table, or a specific record, in the database.
     * @param string $thing
     * @param mixed|null $data
     * @return object|null
     */
    abstract function merge(string $thing, mixed $data): object | null;

    /**
     * Deletes all records in a table, or a specific record, from the database.
     * @param string $thing
     */
    abstract function delete(string $thing): object | null;
}