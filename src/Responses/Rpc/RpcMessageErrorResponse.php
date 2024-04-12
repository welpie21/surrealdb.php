<?php

namespace Surreal\Responses\Websocket;

use Surreal\Exceptions\SurrealException;
use Surreal\Responses\ResponseInterface;

class RpcMessageErrorResponse implements ResponseInterface
{
    const KEYS = ["error", "id"];

    public readonly string $id;
    public readonly array $error;

    /**
     * @throws SurrealException
     */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->error = $data["error"];

        throw new SurrealException($this->error["message"], $this->error["code"]);
    }
}