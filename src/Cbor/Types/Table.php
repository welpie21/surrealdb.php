<?php

namespace Surreal\Cbor\Types;

class Table
{
    private string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public static function fromString(string $table): Table
    {
        return new Table($table);
    }

    public function toString(): string
    {
        return $this->table;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function __toString(): string
    {
        return $this->table;
    }
}