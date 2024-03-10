<?php

namespace Surreal\classes\responses;

use Surreal\classes\exceptions\SurrealException;
use Surreal\interface\ResponseInterface;

class HTTPErrorResponse implements ResponseInterface
{
    const array KEYS = ["code", "details", "description", "information"];

    public readonly int $code;
    public readonly mixed $details;
    public readonly string $description;
    public readonly mixed $information;

    /**
     * @throws SurrealException
     */
    public function __construct(array $data)
    {
        $this->code = $data["code"];
        $this->details = $data["details"];
        $this->description = $data["description"];
        $this->information = $data["information"];

        throw new SurrealException($this->information);
    }
}