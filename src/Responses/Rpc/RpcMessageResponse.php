<?php

namespace Surreal\Responses\Rpc;

use Surreal\Responses\ResponseInterface;

class RpcMessageResponse implements ResponseInterface
{
    const KEYS = ["id", "result"];

    public readonly string $id;
    public readonly mixed $result;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->result = $data["result"];
    }
}