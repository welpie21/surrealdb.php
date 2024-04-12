<?php

namespace Surreal\Responses\Http;

use Surreal\Exceptions\SurrealException;

class HTTPErrorResponse
{
    const KEYS = ["code", "details", "description", "information"];

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