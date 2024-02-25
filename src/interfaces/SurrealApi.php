<?php

namespace Surreal\interfaces;

interface SurrealApi
{
    /**
     * @return int
     */
    public function status(): int;

    /**
     * @return string|null
     */
    public function version(): ?string;

    /**
     * Imports a file into the database.
     * @param string $content
     * @param string $username
     * @param string $password
     * @return string
     */
    public function import(string $content, string $username, string $password): string;

    /**
     * Exports the database to a file.
     * @param string $username
     * @param string $password
     * @return string
     */
    public function export(string $username, string $password): string;

    /**
     * Signs in a user to the database.
     * @param mixed $data
     * @return string|null
     */
    public function signin(mixed $data): ?string;

    /**
     * Sign up a new user to the database. If the authorization argument is not provided,
     * it will use the authorization value that was set previously.
     * @param mixed $data
     * @return string|null
     */
    public function signup(mixed $data): ?string;

    /**
     * Creates a new record in the database.
     * @param string $table
     * @param mixed $data
     * @return object|null
     */
    public function create(string $table, mixed $data): ?object;

    /**
     * Updates a single record in the database.
     * @param string $thing
     * @param mixed $data
     * @return object|null
     */
    public function update(string $thing, mixed $data): ?object;

    /**
     * @param string $thing
     * @param mixed $data
     * @return object|null
     */
    public function merge(string $thing, mixed $data): ?object;

    /**
     * Deletes a record from the database.
     * @param string $thing
     * @return object|null
     */
    public function delete(string $thing): ?object;

    /**
     * Posts a query to the database and returns the result.
     * @param string $query
     * @param array|null $params - Params for to use inside the query ( only WS supported )
     * @return object|array|null
     */
    public function sql(string $query, ?array $params = null): array|object|null;
}