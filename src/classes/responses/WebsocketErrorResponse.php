<?php

namespace Surreal\classes\responses;

use Surreal\classes\exceptions\SurrealException;
use Surreal\interface\ResponseInterface;

class WebsocketErrorResponse implements ResponseInterface
{
    const array KEYS = ["error", "id"];

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