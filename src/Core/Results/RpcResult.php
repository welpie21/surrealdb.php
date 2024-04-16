<?php

namespace Surreal\Core\Results;

use Surreal\Exceptions\SurrealException;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\Types\RpcErrorResponse;
use Surreal\Responses\Types\RpcResponse;

class RpcResult implements ResultInterface
{
    /**
     * @throws SurrealException
     */
    public static function from(ResponseInterface $response): mixed
    {
        return match ($response::class) {
            RpcResponse::class => $response->data(),
            RpcErrorResponse::class => throw new SurrealException($response->data(), $response->status),
            default => throw new SurrealException('Unknown response type')
        };
    }
}