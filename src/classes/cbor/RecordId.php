<?php

namespace Surreal\classes\cbor;

use Surreal\interfaces\CBORInterface;

readonly class RecordId implements CBORInterface
{
    public string $table;
    public string $id;

    public function __constructor(string $table, string $id): void
    {
        $this->table = $table;
        $this->id = $id;
    }
}