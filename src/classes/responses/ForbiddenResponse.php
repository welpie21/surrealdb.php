<?php

namespace Surreal\classes\responses;

use Surreal\abstracts\AbstractResponse;
use Surreal\classes\exceptions\SurrealException;

/**
 * For the forbidden response the request has to respond with "code" = 403
 * the rest doesn't matter what value it has as long as it's present in the response
 */
class ForbiddenResponse extends AbstractResponse
{
    const array KEYS = ["code", "details", "information"];

    public readonly int $code;
    public readonly mixed $details;
    public readonly mixed $information;

    /**
     * @throws SurrealException
     */
    public function __construct(array $data)
    {
        $this->code = $data["code"];
        $this->details = $data["details"];
        $this->information = $data["information"];

        throw new SurrealException($this->information);
    }
}