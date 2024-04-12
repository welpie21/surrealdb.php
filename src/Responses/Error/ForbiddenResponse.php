<?php

namespace Surreal\Responses\Error;

use Surreal\Exceptions\SurrealException;
use Surreal\Exceptions\SurrealForbiddenException;
use Surreal\Responses\ResponseInterface;

/**
 * For the forbidden response the request has to respond with "code" = 403
 * the rest doesn't matter what value it has as long as it's present in the response
 */
class ForbiddenResponse implements ResponseInterface
{
    const KEYS = ["code", "details", "information"];

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

        throw new SurrealForbiddenException($this->information);
    }
}