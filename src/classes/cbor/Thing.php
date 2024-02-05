<?php

namespace Surreal\classes\cbor;

use Surreal\interfaces\CBORInterface;

readonly class Thing implements CBORInterface
{
    public ?string $id;
    public string $table;

    public function __constructor(string $table, ?string $id): void
    {
        $this->table = $table;

        if($this->id) {
            $this->id = $id;
        }
    }
}