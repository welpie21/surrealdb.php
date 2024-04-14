<?php

namespace Surreal\Responses;

use Beau\CborPHP\exceptions\CborException;
use JsonException;
use src\Curl\HttpContentType;

readonly class RpcResponse implements ResponseInterface
{
    public int $id;
    public mixed $result;

    /**
     * @inheritDoc
     * @throws CborException|JsonException
     */
    public static function from(HttpContentType $type, string $body, int $status): ResponseInterface
    {
        $response = ResponseParser::parse($type, $body);

    }
}