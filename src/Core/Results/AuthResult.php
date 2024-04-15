<?php

namespace Surreal\Core\Results;

use Surreal\Core\AbstractSurreal;
use Surreal\Responses\ResponseInterface;
use Surreal\Responses\Types\RpcResponse;

class AuthResult implements ResultInterface
{
    public static function from(ResponseInterface $response): mixed
    {
        return match($response::class) {
            RpcResponse::class => $response->result,
            default => null
        };
    }

    public static function requiredHTTPHeaders(AbstractSurreal $client): array
    {
        return [
            'Content-Type: application/cbor',
            'Accept: application/cbor',
        ];
    }
}