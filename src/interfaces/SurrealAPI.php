<?php

namespace Surreal\interfaces;

use Surreal\classes\SurrealResponse;

interface SurrealAPI
{
    /**
     * @return int
     */
    public function status(): int;

    /**
     * @return string|null
     */
    public function version(): string|null;

    /**
     * Imports a file into the database.
     * @param string $content
     * @return string
     */
    public function import(string $content): string;

    /**
     * Exports the database to a file.
     * @return string
     */
    public function export(): string;

    /**
     * Signs in a user to the database.
     * @param mixed $data
     * @return object|null
     */
    public function signin(mixed $data): object|null;

    /**
     * Sign up a new user to the database. If the authorization argument is not provided,
     * it will use the authorization value that was set previously.
     * @param mixed $data
     * @return mixed
     */
    public function signup(mixed $data): mixed;

    /**
     * Creates a new record in the database.
     * @param string $table
     * @param mixed $data
     * @return SurrealResponse
     */
    public function create(string $table, mixed $data): SurrealResponse;

    /**
     * Updates a single record in the database.
     * @param string $thing
     * @param mixed $data
     * @return SurrealResponse
     */
    public function update(string $thing, mixed $data): SurrealResponse;

    /**
     * @param string $thing
     * @param mixed $data
     * @return SurrealResponse
     */
    public function merge(string $thing, mixed $data): SurrealResponse;

    /**
     * Deletes a record from the database.
     * @param string $thing
     * @return SurrealResponse
     */
    public function delete(string $thing): SurrealResponse;

    /**
     * Posts a query to the database and returns the result.
     * @param string $query
     * @return mixed
     */
    public function sql(string $query): mixed;
}