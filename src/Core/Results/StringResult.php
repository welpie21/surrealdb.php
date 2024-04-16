<?php

namespace Surreal\Core\Results;

use Surreal\Exceptions\SurrealException;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\Types\StringErrorResponse;
use Surreal\Responses\Types\StringResponse;

readonly class StringResult implements ResultInterface
{
    /**
     * @throws SurrealException
     */
    public static function from(ResponseInterface $response): mixed
    {
        return match ($response::class) {
            StringResponse::class => $response->data(),
            StringErrorResponse::class => throw new SurrealException($response->data(), $response->status),
            default => null
        };
    }
}