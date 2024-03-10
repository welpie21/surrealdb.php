<?php

namespace Surreal\classes\responses;

use Surreal\interface\ResponseInterface;

class WebsocketResponse implements ResponseInterface
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