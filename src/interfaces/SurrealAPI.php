<?php

namespace Surreal\interfaces;

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
     * @param string $path
     * @return string
     */
    public function import(string $path): string;

    /**
     * Exports the database to a file.
     * @return string
     */
    public function export(): string;

    /**
     * Signs in a user to the database.
     * @param mixed $data
     * @return string|null
     */
    public function signin(mixed $data): string|null;

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
     * @return object|null
     */
    public function create(string $table, mixed $data): object|null;

    /**
     * Updates a single record in the database.
     * @param string $thing
     * @param mixed $data
     * @return object|null
     */
    public function update(string $thing, mixed $data): object|null;

    /**
     * @param string $thing
     * @param mixed $data
     * @return object|null
     */
    public function merge(string $thing, mixed $data): object|null;

    /**
     * Deletes a record from the database.
     * @param string $thing
     * @return object|null
     */
    public function delete(string $thing): object|null;

    /**
     * Posts a query to the database and returns the result.
     * @param string $query
     * @return object|array|null
     */
    public function sql(string $query): array|object|null;
}