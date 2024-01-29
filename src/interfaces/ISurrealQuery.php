<?php

namespace Surreal\interfaces;

interface ISurrealQuery
{
    function query(string $query): object | null;

    function create(object $data): object | null;

    function delete(string $record): object | null;

    function update(string $record, object $data): object | null;
}