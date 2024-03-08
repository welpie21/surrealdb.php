<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;
use Surreal\classes\exceptions\SurrealException;

class WebsocketErrorResponse extends AbstractResponse
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