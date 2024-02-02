<?php

namespace Surreal\interfaces;

use Surreal\enums\AuthMode;

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
     * @param AuthMode $mode
     * @return mixed
     */
    public function signin(
        AuthMode $mode
    ): mixed;

    /**
     * @param string $namespace
     * @param string $database
     * @return mixed
     */
    public function signup(string $namespace, string $database): mixed;

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
     * @return mixed
     */
    public function sql(string $query): mixed;
}