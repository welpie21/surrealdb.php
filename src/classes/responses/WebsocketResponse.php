<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;

class WebsocketResponse extends AbstractResponse
{
    const array KEYS = ["id", "result"];

    public readonly string $id;
    public readonly mixed $result;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->result = $data["result"];
    }
}